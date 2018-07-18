<?php

namespace PanteraFox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PanteraFox\Params;
use PanteraFox\Services\AppService;
use PanteraFox\User;
use PanteraFox\UserPhotos;
use PanteraFox\UserVideo;

class ProfileController extends Controller
{
    public function index (Request $request, $id)
    {
        $userProfile = User::where('id', $id)
            ->with(['userPhotos' => function ($query) {
                $query->limit(8)->orderBy('id', 'desc')
                    ->with('top');
            }])
            ->with(['userVideos' => function ($query) {
                $query->limit(6)->orderBy('id', 'desc');
            }])
            ->with('avatar')
            ->with('cover')
            ->first();

        if(is_null($userProfile))
        {
            return abort(404);
        }

        $section = $request->get('s') == 'video' ? 'video' : 'photo';
        $params = Params::find(1);
        $top_views = $params->top_views;
        $userProfile->isOwn = false;
        $userProfile->fullName = $userProfile->first_name . ' ' . $userProfile->last_name;


        if ($userProfile->origin == 'native')
        {
            $userProfile->avatar = $userProfile->getRelation('avatar')->thumb_link . '?' . $userProfile->getRelation('avatar')->cache_token;
        }
        if(isset(Auth::user()->id) && Auth::user()->id == $userProfile->id)
        {
            $userProfile->isOwn = true;
        }

        if($section == 'photo')
        {
            $countPhotos = UserPhotos::where('user_id', $id)->count();
            foreach ($userProfile->getRelation('userPhotos') as $photo)
            {
                $photo->topers = [];
                $topers = [];
                foreach ($photo->getRelation('top') as $top)
                {
                    $topers[] = $top->user_id;
                }
                $photo->topers = array_merge($topers, $photo->topers);
                $photo->owner = User::where('id', $id)->first();
            }

            return view('profile', [
                'section' => $section,
                'userProfile' => $userProfile,
                'countContent' => $countPhotos
            ]);
        }



        if($section == 'video')
        {
            AppService::updateTopViews();

            $countVideos = UserVideo::where('user_id', $id)->count();
            foreach ($userProfile->getRelation('userVideos') as $video)
            {
                $raiting = floor($video->views * 100 / $top_views);
                $raiting = ($raiting < 5 ? 5 : $raiting);
                $raiting = ($raiting > 95 ? 95 : $raiting);
                $video->raiting = $raiting;
            }

            return view('profile', [
                'section' => $section,
                'userProfile' => $userProfile,
                'countContent' => $countVideos
            ]);
        }

    }
}
