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
    public function content($countryName, $content)
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
                            WHERE users.country_id = ?
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
            AppService::updateTopViews();
            $contentLimit = 12;
            $params = Params::find(1);
            $top_views = $params->top_views;

            $videos = DB::select(DB::raw("SELECT user_videos.* FROM user_videos
                                LEFT JOIN users ON users.id = user_videos.user_id
                                LEFT JOIN countries ON users.country_id = countries.id
                                WHERE countries.name = 'switzerland'
                                LIMIT $contentLimit"));

            $countVideos = DB::select(DB::raw("SELECT COUNT(user_videos.id) as q FROM user_videos
                                LEFT JOIN users ON users.id = user_videos.user_id
                                LEFT JOIN countries ON users.country_id = countries.id
                                WHERE countries.name = 'switzerland'"))[0]->q;

            foreach ($videos as $video)
            {
                $raiting = floor($video->views * 100 / $top_views);
                $raiting = ($raiting < 5 ? 5 : $raiting);
                $raiting = ($raiting > 95 ? 95 : $raiting);
                $video->raiting = $raiting;
            }

            return view('country-content', [
                'country' => $country,
                'content' => $videos,
                'section' => 'video',
                'countContent' => $countVideos,
                'contentLimit' => $contentLimit
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
    }

    public function world($content)
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
            AppService::updateTopViews();
            $contentLimit = 12;
            $params = Params::find(1);
            $top_views = $params->top_views;

            $videos = DB::select(DB::raw("SELECT * FROM user_videos ORDER BY views desc, id desc limit $contentLimit"));

            $countVideos = DB::select(DB::raw("SELECT COUNT(user_videos.id) as q FROM user_videos"))[0]->q;

            foreach ($videos as $video)
            {
                $raiting = floor($video->views * 100 / $top_views);
                $raiting = ($raiting < 5 ? 5 : $raiting);
                $raiting = ($raiting > 95 ? 95 : $raiting);
                $video->raiting = $raiting;
            }

            return view('country-content', [
                'country' => $country,
                'content' => $videos,
                'section' => 'video',
                'countContent' => $countVideos,
                'contentLimit' => $contentLimit
            ]);
        }

        return ['asd'=>$content];
    }


}
