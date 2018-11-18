<?php

namespace PanteraFox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PanteraFox\Country;
use PanteraFox\Params;
use PanteraFox\Services\AppService;
use PanteraFox\Services\VideoManager;
use PanteraFox\User;

class AppController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AppService $appService, VideoManager $videoManager)
    {
        //TODO: IF IT WILL ENABLED IN FUTURE - THIS QUERY CALCULATE TOP OF COUNTRIES BY COUNT LIKE OF PHOTOS
//        $topCountries = DB::select(DB::raw("SELECT valid_countries.*, sum(top.raiting) as raiting
//            FROM user_photos
//            LEFT JOIN
//            (SELECT photo_id, COUNT(photo_top.id) AS raiting  FROM photo_top GROUP BY `photo_id` ORDER BY raiting DESC) as top
//            ON top.photo_id = user_photos.id
//            LEFT JOIN users ON users.id = user_photos.user_id
//            LEFT JOIN
//            (
//                SELECT countries.*, COUNT(*) as count_photos FROM user_photos
//                LEFT JOIN users ON users.id = user_photos.user_id
//                LEFT JOIN countries ON countries.id = users.country_id
//                GROUP BY countries.id
//                HAVING count_photos > 3
//            ) AS valid_countries ON valid_countries.id = users.country_id
//            WHERE valid_countries.name IS NOT NULL
//            GROUP BY valid_countries.name
//            ORDER BY raiting DESC, user_photos.id DESC"));

        if (isset(Auth::user()->id)){
            $topCountries = DB::select(DB::raw("SELECT c.name, c.id as country_id, u.id, sum(sum_views) as country_rating FROM countries c
                          LEFT JOIN users u ON u.country_id = c.id
                          LEFT JOIN
                          (select *, sum(views) as sum_views
                           from user_videos
                           group by user_id)
                          uv ON uv.user_id = u.id
                          WHERE c.id != (SELECT country_id FROM users where id = :user_id)
                          group by u.country_id
                          order by country_rating desc
                        LIMIT 3"), [':user_id' => Auth::user()->id]);

            $userCountry = collect(DB::select(DB::raw("SELECT c.name, c.id as country_id, u.id, sum(sum_views) as country_rating FROM countries
                          LEFT JOIN users u ON u.country_id = countries.id
                          right join countries c on u.country_id = c.id
                          LEFT JOIN
                          (select *, sum(views) as sum_views
                           from user_videos
                           group by user_id)
                          uv ON uv.user_id = u.id
                          WHERE u.id = :user_id"), [':user_id' => Auth::user()->id]))->first();
            array_unshift($topCountries, $userCountry);
        } else {
            $topCountries = DB::select(DB::raw("SELECT c.name, c.id as country_id, u.id, sum(sum_views) as country_rating FROM countries
                          LEFT JOIN users u ON u.country_id = countries.id
                          right join countries c on u.country_id = c.id
                          LEFT JOIN
                          (select *, sum(views) as sum_views
                           from user_videos
                           group by user_id)
                          uv ON uv.user_id = u.id
                          group by u.country_id
                          order by country_rating desc
                        LIMIT 4"));
        }

        foreach ($topCountries as $index => $country)
        {
            //TODO: IF IT WILL ENABLED IN FUTURE - THIS QUERY RETRIEVE TOP PHOTOS TO COUNTRY
//            $topCountryPhoto = DB::select(DB::raw("SELECT user_photos.*, top.raiting FROM user_photos
//            LEFT JOIN users ON users.id = user_photos.user_id
//            LEFT JOIN countries ON countries.id = users.country_id
//            LEFT JOIN
//            (
//                SELECT  photo_top.photo_id, COUNT(photo_top.id) as raiting FROM photo_top
//                GROUP BY photo_top.photo_id
//            ) AS top ON top.photo_id = user_photos.id
//            WHERE countries.id = ?
//            ORDER BY top.raiting DESC, user_photos.id DESC
//            LIMIT 4"), [$country->id]);


            $topCountryVideo = DB::select(DB::raw("select user_videos.*, u.first_name, u.last_name
                                from user_videos
                                left join users u on user_videos.user_id = u.id
                                left join countries c on u.country_id = c.id
                                where c.id = ?
                                order by views desc
                                limit 3"), [$country->country_id]);

//            var_dump($topCountryVideo);exit;

            $topCountryVideo = $videoManager->butifyViewsCount($topCountryVideo);

//            foreach ($topCountryPhoto as $index_photo => $photo)
//            {
//                $toppers_query = DB::select(DB::raw("SELECT user_id FROM photo_top WHERE photo_id = ?"), [$photo->id]);
//                $toppers = [];
//                foreach ($toppers_query as $topper)
//                {
//                    $toppers[] = $topper->user_id;
//                }
//                $owner = User::where('id', $photo->user_id)->with('avatar')->first();
//                if($owner->origin == 'native')
//                {
//                    $owner->avatar = $owner->getRelation('avatar')->thumb_link . '?' . $owner->getRelation('avatar')->cache_token;
//                }
//                $owner->fullName = $owner->first_name . ' ' .$owner->last_name;
//                $topCountryPhoto[$index_photo]->topers = $toppers;
//                $topCountryPhoto[$index_photo]->owner = $owner;
//            }
            $topCountries[$index]->video = $topCountryVideo;
        }

        return view(
            'main',
            [
                'topCountries' => $topCountries,
                'countries' => Country::all(),
            ]
        );
    }
}
