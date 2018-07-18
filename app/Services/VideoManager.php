<?php

namespace PanteraFox\Services;


use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PanteraFox\UserVideo;

class VideoManager
{
    /**
     * @param string $videoLink
     *
     * @return array
     */
    public function add($videoLink)
    {
        if(!$this->isYoutubeLink($videoLink))
        {
            return [
                'error' => true,
                'message' => 'video.notYT'
                ];
        }

        $ytid = $this->getYTIDFromLink($videoLink);

        UserVideo::create([
           'user_id' => Auth::user()->id,
            'link' => $videoLink,
            'video_id' => $ytid
        ]);

        return ['success' => true];
    }

    /**
     * @param string $id
     * @return array
     */
    public function view ($id)
    {
        $video = UserVideo::find($id);
        $video->views = $video->views + 1;
        $video->save();

        return ['success' => true];
    }

    /**
     * @param string $id
     * @return array
     */
    public function remove($id)
    {
        UserVideo::where('id',$id)->delete();

        return ['success' => true];
    }

    /**
     * @param $user_id
     * @param $offset
     * @return array
     */
    public function loadmore($user_id, $offset)
    {
        $isOwn = false;
        if(isset(Auth::user()->id) && Auth::user()->id == $user_id)
        {
            $isOwn = true;
        }
        $videos = UserVideo::where('user_id',$user_id)->orderBy('id','desc')->offset($offset)->limit(6)->get();
        $video_renders = [];
        foreach ($videos as $video)
        {
            $video_renders[] = view('partial.video',
                [
                    'video' => $video,
                    'isOwn' => $isOwn
                ])->render();
        }
        return [
            'success' => true,
            'videos' => $video_renders
        ];
    }

    /**
     * @param string $videoLink
     * @return bool
     */
    private function isYoutubeLink($videoLink)
    {
        return (bool) preg_match('/http(?:s?):\/\/(?:www\.)?youtu(?:be\.com\/watch\?v=|\.be\/)([\w\-\_]*)(&(amp;)?‌​[\w\?‌​=]*)?/',$videoLink);
    }

    /**
     * @param $videoLink
     * @return string
     * @throws \Exception
     */
    private function getYTIDFromLink($videoLink)
    {
        parse_str( parse_url( $videoLink, PHP_URL_QUERY ), $url_parts );
        if(!isset($url_parts['v']))
        {
            throw new \Exception("Video link $videoLink does not have a YouTube ID");
        }
        return $url_parts['v'];
    }
}