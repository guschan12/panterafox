<?php

namespace PanteraFox\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use PanteraFox\PhotoTop;
use PanteraFox\User;
use PHPImageWorkshop\ImageWorkshop;
use PanteraFox\UserPhotos;

class PhotoManager
{
    /**
     * @param UploadedFile $photo
     *
     * @return bool
     */
    public function addPhoto(UploadedFile $photo)
    {
        $filename = $this->saveSource($photo);
        $this->saveThumb($filename);

        $sourceLink = config('app.url') . "/uploads/photos/source/$filename";
        $thumbLink = config('app.url') . "/uploads/photos/thumb/$filename";

        $userPhotos = new UserPhotos();

        $userPhotos->user_id = auth()->user()->id;
        $userPhotos->thumb_link = $thumbLink;
        $userPhotos->source_link = $sourceLink;
        $userPhotos->cache_token = uniqid();

        if($userPhotos->save()) {
            return true;
        }
        return false;
    }

    /**
     * @param string $id
     * @param string $direction
     * @throws \PHPImageWorkshop\Core\Exception\ImageWorkshopLayerException
     * @throws \PHPImageWorkshop\Exception\ImageWorkshopException
     */
    public function rotate($id, $direction)
    {
        $photo = UserPhotos::where([
            ['user_id',Auth::user()->id],
            ['id', $id]
        ])->first();

        $url_parts = explode('/', $photo->thumb_link);
        $filename = end($url_parts);
        $direction = $direction == 'left' ? '-' : '';
        $degrees = $direction."90";
        $cacheToken = uniqid();

        $layer = ImageWorkshop::initFromPath(config('app.url') . "/uploads/photos/thumb/$filename");
        $layer->rotate($degrees);
        $layer->save(public_path() . '/uploads/photos/thumb', $filename, true, null, 85);

        $layer = ImageWorkshop::initFromPath(config('app.url') . "/uploads/photos/source/$filename");
        $layer->rotate($degrees);
        $layer->save(public_path() . '/uploads/photos/source', $filename, true, null, 85);

        $photo->cache_token = $cacheToken;
        $photo->save();
    }

    public function getSourceByid($id)
    {
        $photo = UserPhotos::where([
            ['user_id',Auth::user()->id],
            ['id', $id]
        ])->first();

        return $photo->source_link . '?' . $photo->cache_token;
    }

    /**
     * @param string $id
     */
    public function removeById($id)
    {
//        var_dump($id);
        $photo = UserPhotos::where([
            ['user_id',Auth::user()->id],
            ['id', $id]
        ])->first();

        UserPhotos::where([
            ['user_id',Auth::user()->id],
            ['id', $id]
        ])->delete();

        $filename = $this->getFilenameByUrl($photo->source_link);

        unlink(public_path() . "/uploads/photos/source/$filename");
        unlink(public_path() . "/uploads/photos/thumb/$filename");
    }

    public function getOneUserPhotoByOffset($offset)
    {
        $photo = UserPhotos::where('user_id', Auth::user()->id)->orderBy('id','desc')->offset($offset - 1)->limit(1)
            ->withCount('top')
            ->with(['top' => function ($query) {
                $query->select('photo_id')->where('user_id', Auth::user()->id);
            }])
            ->first();
        if (!$photo)
        {
            return false;
        }
        return [
            'thumb_link' => $photo->thumb_link,
            'source_link' => $photo->source_link,
            'top_count' => $photo->top_count,
            'top' => $photo->top,
            'id' => $photo->id,
        ];
    }

    /**
     * @param string $offset
     * @return array
     * @throws \Throwable
     */
    public function loadMore($user_id, $offset)
    {
        $isOwn = false;
        $owner = User::find($user_id);
        $photo_renders = [];

        if(isset(Auth::user()->id) && Auth::user()->id == $user_id)
        {
            $isOwn = true;
        }

        $loadedPhotos = UserPhotos::where('user_id', $user_id)->orderBy('id','desc')->offset($offset)->limit(8)->get();

        foreach ($loadedPhotos as $index => $photo)
        {
            $tops = PhotoTop::select('user_id')->where('photo_id', $photo->id)->get();
            $topers = [];
            foreach ($tops as $top)
            {
                $topers[] = $top->user_id;
            }

            $photo->owner = $owner;
            $photo->topers = $topers;

            $photo_renders[] =  view('partial.photo', [
                'photo' => $photo,
                'index' => $offset + $index,
                'isOwn' => $isOwn,
                'showOwner' => false
            ])->render();
        }

        return $photo_renders;
    }

    public function loadMoreForCountry($countryName, $offset)
    {
        $countryManager = new CountryManager();
        $country_id = $countryManager->getCountryIdByName($countryName);
        $photo_renders = [];
        $loadedPhotos = UserPhotos::whereHas('user', function ($query) use ($country_id){
            $query->where('country_id',$country_id);
        })->offset($offset)->limit(16)->get();

        foreach ($loadedPhotos as $index => $photo)
        {
            $tops = PhotoTop::select('user_id')->where('photo_id', $photo->id)->get();
            $topers = [];
            $owner = User::find($photo->user_id);
            foreach ($tops as $top)
            {
                $topers[] = $top->user_id;
            }
            $photo->owner = $owner;
            $photo->topers = $topers;
            $photo_renders[] =  view('partial.photo', [
                'photo' => $photo,
                'index' => $offset + $index,
                'isOwn' => false,
                'showOwner' => false
            ])->render();
        }

        return $photo_renders;
    }

    /**
     * @param string $photoId
     * @return bool
     */
    public function topDown($photoId)
    {
        PhotoTop::where([
            ['user_id',Auth::user()->id],
            ['photo_id', $photoId]
        ])->delete();
        return true;
    }

    /**
     * @param string $photoId
     * @return bool
     */
    public function topUp($photoId)
    {
        PhotoTop::create([
            'user_id' => Auth::user()->id,
            'photo_id' => $photoId
        ]);

        return true;
    }

    /**
     * @param UploadedFile $photo
     *
     * @return string
     */
    private function saveSource(UploadedFile $photo)
    {
        $destinationPath = public_path() . '/uploads/photos/source/';
        $sourceName = uniqid() . '.' . $photo->getClientOriginalExtension();

        $photo->move($destinationPath, $sourceName);

        return $sourceName;
    }

    /**
     * @param $filename
     */
    private function saveThumb($filename)
    {
        $sourcePath = public_path() . '/uploads/photos/source/';
        $thumbPath = public_path() . '/uploads/photos/thumb';

        $layer = ImageWorkshop::initFromPath($sourcePath . $filename);
        $layer->cropMaximumInPixel(0, 0, "MT");
        $layer->resizeInPixel(300, 300);

        $layer->save($thumbPath, $filename, true, null, 85);
    }

    private function getFilenameByUrl($url)
    {
        $fileArr = explode('/', $url);
        return end($fileArr);
    }
}