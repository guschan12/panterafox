<?php
/**
 * Created by PhpStorm.
 * User: Hamlet
 * Date: 27.09.2018
 * Time: 20:31
 */

namespace PanteraFox\Subscription\Application;


use Illuminate\Support\Facades\DB;
use PanteraFox\Subscription\Data\Entity\UserNews;
use PanteraFox\Subscription\Data\UserNewsRepository;

class UserNewsService
{
    /**
     * @var UserNewsRepository
     */
    private $userNewsRepository;

    public function __construct(UserNewsRepository $userNewsRepository)
    {
        $this->userNewsRepository = $userNewsRepository;
    }

    /**
     * @param string $subscriberId
     * @param string $profileId
     *
     * @throws \Throwable
     */
    public function createSubscriptionNews($subscriberId, $profileId)
    {
        $userNews = $this->getSubscriptionNewsEntity($subscriberId, $profileId);
        $this->userNewsRepository->createNews($userNews);
    }

    public function getNewsForUser($userId)
    {
       $new = collect($this->userNewsRepository::where([
            'user_id' => $userId,
            'status' => UserNews::STATUS_NEW
        ])->orderBy('created_at', 'desc')->get())->toArray();
       if (count($new) < 5){
           $read = collect($this->userNewsRepository::where([
               'user_id' => $userId,
               'status' => UserNews::STATUS_READ
           ])->limit(5)->orderBy('created_at', 'desc')->get())->toArray();

           if (count($read) > 0){
               $j = 0;
               for ($i = count($new); $i <= 5; $i++){
                   if (isset($read[$j])){
                       $new[$i] = $read[$j];
                   }
                   $j++;
               }
           }
       }

        return $new;

    }

    public function getCountNewsForUser($userId)
    {
        return $this->userNewsRepository::where([
            'user_id' => $userId,
            'status' => UserNews::STATUS_NEW
        ])->count();
    }

    /**
     * @param string $subscriberId
     * @param string $profileId
     *
     * @return UserNews
     * @throws \Throwable
     */
    private function getSubscriptionNewsEntity($subscriberId, $profileId)
    {
        return (new UserNews())
            ->setUserId($profileId)
            ->setContent($this->getSubscriptionNewsContent($subscriberId))
            ->setType(UserNews::SUBSCRIPTION_TYPE);
    }

    /**
     * @param $subscriberId
     *
     * @return string
     * @throws \Throwable
     */
    private function getSubscriptionNewsContent($subscriberId)
    {
        $subscriber = collect(DB::select(DB::raw("select users.id, users.first_name, users.last_name, users.avatar, a.thumb_link
                                    from users
                                      left join avatars a on users.id = a.user_id
                                      where users.id = ?"), [$subscriberId]))->first();
        $subscriber->photo = $subscriber->avatar ?: $subscriber->thumb_link;

        return view('partial.infrastructure.news-subscription', [
            'subscriber' => $subscriber
        ])->render();
    }
}