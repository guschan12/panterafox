<?php

namespace PanteraFox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PanteraFox\Country;
use PanteraFox\Params;
use PanteraFox\PhotoTop;
use PanteraFox\Services\AppService;
use PanteraFox\Services\PhotoManager;
use PanteraFox\Services\VideoManager;
use PanteraFox\User;

class CountryController extends Controller
{
    public function index()
    {
        return view('country', [
            'countries' => Country::all()
        ]);
    }

    /**
     * @param string $countryName
     * @param string $content
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function content(Request $request, $countryName, $content, VideoManager $videoManager)
    {
        $country = Country::where('name', $countryName)->first();
        $content = $content ?: 'photo';
        if (!$country || !in_array($content, ['photo', 'video'])) {
            return abort(404);
        }

        if ($content == 'photo') {
            $contentLimit = 16;
            $countryPhotos = DB::select(DB::raw("SELECT user_photos.* FROM `user_photos`
                            LEFT JOIN users ON user_photos.user_id = users.id
                            LEFT JOIN
                              (select count(*) as count, photo_id from photo_top group by photo_id) as top
                            on user_photos.id = top.photo_id
                            WHERE users.country_id = ?
                            order by top.count desc , user_photos.id desc
                            LIMIT $contentLimit"), [$country->id]);
            $countPhotos = DB::select(DB::raw("SELECT COUNT(user_photos.id) AS q FROM `user_photos`
                            LEFT JOIN users ON user_photos.user_id = users.id
                            WHERE users.country_id = ?"), [$country->id])[0]->q;

            foreach ($countryPhotos as $photo) {
                $owner = User::where('id', $photo->user_id)->with('avatar')->first();
                $tops = PhotoTop::select('user_id')->where('photo_id', $photo->id)->get();

                $topers = [];
                foreach ($tops as $top) {
                    $topers[] = $top->user_id;
                }

                $photo->owner = $owner;
                $photo->topers = $topers;
            }

            return view('country-content', [
                'country' => $country,
                'content' => $countryPhotos,
                'section' => 'photo',
                'countContent' => $countPhotos,
                'contentLimit' => $contentLimit,
            ]);
        }

        if ($content == 'video') {
            $contentLimit = 12;
            $latest = $request->get('order') == 'new' ? true : false;
            $load_more_xhr = $latest ? 'loadmore?order=new' : "loadmore";

            $video_sql = "SELECT user_videos.*, users.first_name, users.last_name FROM user_videos
                                LEFT JOIN users ON users.id = user_videos.user_id
                                LEFT JOIN countries ON users.country_id = countries.id
                                WHERE countries.name = :countryName
                                ORDER BY";
            if(!$latest)
            {
                $video_sql .= " user_videos.views DESC ,";
            }
            $video_sql .= " user_videos.created_at DESC LIMIT $contentLimit";

            $videos = DB::select(DB::raw($video_sql), [':countryName' => $countryName]);

            $countVideos = DB::select(DB::raw("SELECT COUNT(user_videos.id) as q FROM user_videos
                                LEFT JOIN users ON users.id = user_videos.user_id
                                LEFT JOIN countries ON users.country_id = countries.id
                                WHERE countries.name = ?"),[$countryName])[0]->q;

            $videos = $videoManager->butifyViewsCount($videos);

            return view('country-content', [
                'country' => $country,
                'content' => $videos,
                'section' => 'video',
                'countContent' => $countVideos,
                'contentLimit' => $contentLimit,
                'load_more_xhr' => $load_more_xhr
            ]);
        }


    }

    public function loadMore
    (
        $countryName,
        $content,
        Request $request,
        PhotoManager $photoManager,
        VideoManager $videoManager
    )
    {
        $offset = $request->post('offset');
        if ($content == 'photo')
        {
            return $photoManager->loadMoreForCountry($countryName, $offset);
        }
        if ($content == 'video')
        {
            $latest = $request->get('order') == 'new' ? true : false;
            return $videoManager->loadMoreForCountry($countryName, $offset, $latest);
        }
    }

    public function world($content, VideoManager $videoManager)
    {
        $content = $content ?: 'photo';
        $country = new Country([
            'name' => 'TOP world',
            'flag_link' => '/images/flags/world.jpg'
        ]);
        if (!in_array($content, ['photo', 'video'])) {
            return abort(404);
        }

        if ($content == 'photo') {
            $contentLimit = 16;
            $worldPhotos = DB::select(DB::raw("SELECT user_photos.*, top.cnt FROM `user_photos` 
                            LEFT JOIN (SELECT photo_id, COUNT(id) as cnt FROM photo_top GROUP BY photo_id) as top ON top.photo_id = user_photos.id
                            ORDER BY cnt desc, user_photos.id desc
                            LIMIT $contentLimit"));
            $countPhotos = DB::select(DB::raw("SELECT COUNT(user_photos.id) AS q FROM `user_photos`"))[0]->q;

            foreach ($worldPhotos as $photo) {
                $owner = User::where('id', $photo->user_id)->with('avatar')->first();
                $tops = PhotoTop::select('user_id')->where('photo_id', $photo->id)->get();

                $topers = [];
                foreach ($tops as $top) {
                    $topers[] = $top->user_id;
                }

                $photo->owner = $owner;
                $photo->topers = $topers;
            }

            return view('country-content', [
                'country' => $country,
                'content' => $worldPhotos,
                'section' => 'photo',
                'countContent' => $countPhotos,
                'contentLimit' => $contentLimit,
            ]);
        }

        if ($content == 'video') {
            $contentLimit = 12;
            $load_more_xhr = "loadmore";

            $videos = DB::select(DB::raw("SELECT user_videos.*, u.first_name, u.last_name FROM user_videos
                LEFT JOIN users u on user_videos.user_id = u.id
                ORDER BY views desc, id desc limit $contentLimit"));
            $videos = $videoManager->butifyViewsCount($videos);

            $countVideos = DB::select(DB::raw("SELECT COUNT(user_videos.id) as q FROM user_videos"))[0]->q;

            return view('country-content', [
                'country' => $country,
                'content' => $videos,
                'section' => 'video',
                'countContent' => $countVideos,
                'contentLimit' => $contentLimit,
                'load_more_xhr' => $load_more_xhr
            ]);
        }
    }

    public function loadMoreWorld
    (
        $content,
        Request $request,
        PhotoManager $photoManager,
        VideoManager $videoManager
    )
    {
        if ($content == 'photo')
        {
            return $photoManager->loadMoreForWorld($request->post('offset'));
        }
        if ($content == 'video')
        {
            return $videoManager->loadMOreForWorld($request->post('offset'));
        }
    }


}
