<?php
namespace PanteraFox\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use PanteraFox\Avatar;
use PHPImageWorkshop\ImageWorkshop;

class AvatarManager
{
    /**
     * @param UploadedFile $file
     * @param array $crop
     *
     * @return bool
     */
    public function updatePhoto(UploadedFile $file, array $crop)
    {
        $avatar = Avatar::where('user_id', Auth::user()->id)->first();
        $avatar_source = $avatar->source_link;
        $old_source_arr = explode('/', $avatar_source);
        $old_filename = end($old_source_arr);

        $new_filename = $this->saveSource($file);
        $this->saveThumb($new_filename, $crop);

        $sourceLink =  "/uploads/avatars/source/$new_filename";
        $thumbLink =  "/uploads/avatars/thumb/$new_filename";

        $avatar->source_link = $sourceLink;
        $avatar->thumb_link = $thumbLink;
        $avatar->cache_token = uniqid();
        $avatar->crop = json_encode($crop);
        $avatar->save();

        if($old_filename !== 'default.jpg')
        {
            unlink(public_path() . "/uploads/avatars/source/$old_filename");
            unlink(public_path() . "/uploads/avatars/thumb/$old_filename");
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
        $avatar = Avatar::where('user_id', Auth::user()->id)->first();
        $old_thumb_link = $avatar->thumb_link;
        $old_thumb_name_arr = explode('/', $old_thumb_link);
        $old_thumb_name = end($old_thumb_name_arr);

        if($old_thumb_name == 'default.jpg')
        {
            $new_filename = $this->saveThumb($old_thumb_name,$crop, true);
            $new_thumb_name = '/uploads/avatars/thumb/' . $new_filename;
            $avatar->thumb_link = $new_thumb_name;
        }
        else
        {
            $this->saveThumb($old_thumb_name,$crop);
        }

        $avatar->cache_token = uniqid();
        $avatar->crop = json_encode($crop);
        $avatar->save();
        return true;
    }

    /**
     * @param UploadedFile $photo
     *
     * @return string
     */
    private function saveSource(UploadedFile $photo)
    {
        $destinationPath = public_path() . '/uploads/avatars/source/';
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
        $sourcePath = public_path() . '/uploads/avatars/source/';
        $thumbPath = public_path() . '/uploads/avatars/thumb';

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
