<?php

namespace PanteraFox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PanteraFox\Subscription;
use PanteraFox\Subscription\Application\UserNewsService;
use PanteraFox\Subscription\Data\Entity\UserNews;
use PanteraFox\Subscription\Data\UserNewsRepository;

class SubscriptionsController extends Controller
{
    /**
     * @param Request $request
     *
     * @return array
     * @throws \Throwable
     */
    public function index(Request $request, UserNewsService $userNewsService)
    {
        $subscriber = $request->post('subscriber');
        $profile = $request->post('profile');
        $action = $request->post('action');

        if ($subscriber == 'false') {
            return ['needLogin' => true];
        }

        if ($action == 'subscribe') {
            $subscription = new Subscription();
            $subscription->subscriber_id = $subscriber;
            $subscription->profile_id = $profile;
            $subscription->save();

            $userNewsService->createSubscriptionNews($subscriber, $profile);
        }

        if ($action == 'unsubscribe') {
            Subscription::where([
                'subscriber_id' => $subscriber,
                'profile_id' => $profile
            ])->delete();
        }

        return ['success' => true];
    }

    /**
     * @param Request $request
     *
     * @return array
     * @throws \Throwable
     */
    public function subscriptionsList(Request $request)
    {
        $profiles = DB::select(DB::raw("select users.id, users.first_name, users.last_name, users.avatar, a.thumb_link
                                    from subscriptions
                                      left join users on users.id = subscriptions.profile_id
                                      left join avatars a on users.id = a.user_id
                                      where subscriber_id = ?"), [$request->post('id')]);

        return [
            'success' => true,
            'profiles' => $this->renderProfiles($profiles),
        ];
    }

    public function subscribersList(Request $request)
    {
        $profiles = DB::select(DB::raw("select users.id, users.first_name, users.last_name, users.avatar, a.thumb_link
                                    from subscriptions
                                      left join users on users.id = subscriptions.subscriber_id
                                      left join avatars a on users.id = a.user_id
                                      where profile_id = ?"), [$request->post('id')]);

        return [
            'success' => true,
            'profiles' => $this->renderProfiles($profiles),
        ];
    }

    public function clearNews(Request $request)
    {
        $id = $request->post('id');
        $news = UserNewsRepository::where([
            'user_id' => $id,
            'status' => UserNews::STATUS_NEW
        ])->get();

        foreach ($news as $row){
            $row->status = UserNews::STATUS_READ;
            $row->save();
        }

        return 'success';
    }

    private function renderProfiles(array $profiles){
        $profile_renders = [];

        foreach ($profiles as $profile)
        {
            $profile->photo = $profile->avatar === null ? $profile->thumb_link : $profile->avatar;
            $profile_renders[] =  view('partial.profile', [
                'profile' => $profile
            ])->render();
        }

        return $profile_renders;
    }
}
