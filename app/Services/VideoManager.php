<?php

namespace PanteraFox\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PanteraFox\Params;
use PanteraFox\UserVideo;

class VideoManager
{
    /** @var null | array */
    private $youTubeResponse = null;

    /**
     * @param string $videoLink
     *
     * @return array
     * @throws \Exception
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
        $views = $this->getYTViews($ytid);
        $title = $this->getYTTitle($ytid);

        UserVideo::create([
           'user_id' => Auth::user()->id,
            'link' => $videoLink,
            'video_id' => $ytid,
            'views' => $views,
            'title' => $title
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
     * @param UserVideo[] $videos
     * @return UserVideo[]
     */
    public function butifyViewsCount($videos)
    {
        foreach ($videos as $video) {
            if (is_array($video)){
                $video['views'] = number_format($video['views'], 0, '', ' ');
            }else{
                $video->views = number_format($video->views, 0, '', ' ');
            }

        }

        return $videos;
    }

    /**
     * @param $user_id
     * @param $offset
     * @return array
     * @throws \Throwable
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
     * @param $countryName
     * @param $offset
     * @return array
     * @throws \Throwable
     */
    public function loadMoreForCountry($countryName, $offset, $latest = false)
    {
        $params = Params::find(1);
        $top_views = $params->top_views;
        $video_renders = [];

        $video_sql = "SELECT user_videos.*, users.first_name, users.last_name FROM user_videos
                                LEFT JOIN users ON users.id = user_videos.user_id
                                LEFT JOIN countries ON users.country_id = countries.id
                                WHERE countries.name = :countryName
                                ORDER BY";
        if (!$latest)
        {
            $video_sql .= " user_videos.views DESC ,";
        }
        $video_sql .= " user_videos.updated_at LIMIT :offset, 12";

        $videos = DB::select(DB::raw("$video_sql"), [':countryName' => $countryName, 'offset' => $offset]);
        foreach ($videos as $video)
        {
            $raiting = floor($video->views * 100 / $top_views);
            $raiting = ($raiting < 5 ? 5 : $raiting);
            $raiting = ($raiting > 95 ? 95 : $raiting);
            $video->raiting = $raiting;
            $video_renders[] = view('partial.video', [
                'video' => $video,
                'isOwn' => false
            ])->render();
        }

        return $video_renders;
    }

    /**
     * @param string|int $offset
     *
     * @return array
     * @throws \Throwable
     */
    public function loadMoreForWorld($offset)
    {
        $params = Params::find(1);
        $top_views = $params->top_views;
        $video_renders = [];
        $videos = DB::select(DB::raw("SELECT user_videos.*, u.first_name, u.last_name FROM user_videos
                left join users u on user_videos.user_id = u.id
                ORDER BY views desc, id desc limit ?, 12"),[$offset]);

        foreach ($videos as $video)
        {
            $raiting = floor($video->views * 100 / $top_views);
            $raiting = ($raiting < 5 ? 5 : $raiting);
            $raiting = ($raiting > 95 ? 95 : $raiting);
            $video->raiting = $raiting;

            $video_renders[] = view('partial.video', [
                'video' => $video,
                'isOwn' => false
            ])->render();
        }

        return $video_renders;
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

    /**
     * @param string $ytid
     * @return string
     */
    private function getYTViews($ytid)
    {
        $videoInfo = $this->getYouTubeVideoInfo($ytid);

        return $videoInfo['items'][0]['statistics']['viewCount'];
    }

    /**
     * @param string $ytid
     * @return string mixed
     */
    private function getYTTitle($ytid)
    {
        $videoInfo = $this->getYouTubeVideoInfo($ytid);

        return $videoInfo['items'][0]['snippet']['title'];
    }


    /**
     * @param string $ytid
     * @return array
     */
    private function getYouTubeVideoInfo($ytid)
    {
        if (null === $this->youTubeResponse) {
            $response = json_decode(
                file_get_contents(
                    "https://www.googleapis.com/youtube/v3/videos?part=statistics,snippet&id=$ytid&key=AIzaSyCe7UB6H928GyEjHaIHeY5ZJiAmyxP_dYc"
                ),
                true);
            $this->youTubeResponse = $response;
        } else {
            $response = $this->youTubeResponse;
        }

        return $response;
    }


}