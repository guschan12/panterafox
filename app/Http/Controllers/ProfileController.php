<?php

namespace PanteraFox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PanteraFox\Services\VideoManager;
use PanteraFox\Subscription;
use PanteraFox\User;
use PanteraFox\UserPhotos;
use PanteraFox\UserVideo;

class ProfileController extends Controller
{
    public function index (Request $request, $id, VideoManager $videoManager)
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

        $userProfile->subscribesCount = Subscription::where('profile_id', $id)->get()->count();

        if(is_null($userProfile))
        {
            return abort(404);
        }

        $section = $request->get('s') == 'photo' ? 'photo' : 'video';
        if (!$userProfile->is_verified)
        {
            $section = 'photo';
        }
        $userProfile->isOwn = false;
        $userProfile->fullName = $userProfile->first_name . ' ' . $userProfile->last_name;


        if ($userProfile->origin == 'native')
        {
            $userProfile->avatar = $userProfile->getRelation('avatar')->thumb_link . '?' . $userProfile->getRelation('avatar')->cache_token;
        }

        if(isset(Auth::user()->id))
        {
            if (Auth::user()->id == $userProfile->id){
                $userProfile->isOwn = true;
            }
            $userProfile->isSubscribed = (bool) Subscription::where([
                'subscriber_id' => Auth::user()->id,
                'profile_id' => $id
            ])->get()->count();
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
            $countVideos = UserVideo::where('user_id', $id)->count();
            $beautyVideos = $videoManager->butifyViewsCount($userProfile->getRelation('userVideos'));
            $userProfile->setRelation('userVideos', $beautyVideos);

            return view('profile', [
                'section' => $section,
                'userProfile' => $userProfile,
                'countContent' => $countVideos
            ]);
        }

    }
}
