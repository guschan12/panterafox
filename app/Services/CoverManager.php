<?php
namespace PanteraFox\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use PanteraFox\Cover;
use PHPImageWorkshop\ImageWorkshop;

class CoverManager
{
    /**
     * @param UploadedFile $file
     * @param array $crop
     *
     * @return bool
     */
    public function updatePhoto(UploadedFile $file, array $crop)
    {
        $cover = Cover::where('user_id', Auth::user()->id)->first();
        $cover_source = $cover->source_link;
        $old_source_arr = explode('/', $cover_source);
        $old_filename = end($old_source_arr);

        $new_filename = $this->saveSource($file);
        $this->saveThumb($new_filename, $crop);

        $sourceLink =  "/uploads/covers/source/$new_filename";
        $thumbLink =  "/uploads/covers/thumb/$new_filename";

        $cover->source_link = $sourceLink;
        $cover->thumb_link = $thumbLink;
        $cover->cache_token = uniqid();
        $cover->crop = json_encode($crop);
        $cover->save();

        if($old_filename !== 'default.jpg')
        {
            unlink(public_path() . "/uploads/covers/source/$old_filename");
            unlink(public_path() . "/uploads/covers/thumb/$old_filename");
        }
        return true;
    }

    /**
     * @param array $crop
     *
     * @return bool
     */
    public function updateCrop(array $crop)
    {
        $cover = Cover::where('user_id', Auth::user()->id)->first();
        $old_thumb_link = $cover->thumb_link;
        $old_thumb_name_arr = explode('/', $old_thumb_link);
        $old_thumb_name = end($old_thumb_name_arr);

        if($old_thumb_name == 'default.jpg')
        {
            $new_filename = $this->saveThumb($old_thumb_name,$crop, true);
            $new_thumb_name = '/uploads/covers/thumb/' . $new_filename;
            $cover->thumb_link = $new_thumb_name;
        }
        else
        {
            $this->saveThumb($old_thumb_name,$crop);
        }

        $cover->cache_token = uniqid();
        $cover->crop = json_encode($crop);
        $cover->save();
        return true;
    }

    /**
     * @param UploadedFile $photo
     *
     * @return string
     */
    private function saveSource(UploadedFile $photo)
    {
        $destinationPath = public_path() . '/uploads/covers/source/';
        $sourceName = uniqid() . '.' . $photo->getClientOriginalExtension();

        $photo->move($destinationPath, $sourceName);

        return $sourceName;
    }

    /**
     * @param string $filename
     * @param array $crop
     * @return string|bool
     */
    private function saveThumb($filename, array $crop, $is_default = false)
    {
        $sourcePath = public_path() . '/uploads/covers/source/';
        $thumbPath = public_path() . '/uploads/covers/thumb';

        $layer = ImageWorkshop::initFromPath($sourcePath . $filename);
        $layer->cropInPixel($crop['width'], $crop['height'], round($crop['x']), round($crop['y']), 'LT');

        if($is_default)
        {
            $new_filename = uniqid() . '.jpg';
            $layer->save($thumbPath, $new_filename, true, null, 85);
            return $new_filename;
        }

        $layer->save($thumbPath, $filename, true, null, 85);

        return true;
    }
}
