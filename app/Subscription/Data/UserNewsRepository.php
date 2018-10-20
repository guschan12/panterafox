<?php

namespace PanteraFox\Subscription\Data;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PanteraFox\Subscription\Data\Entity\UserNews;

class UserNewsRepository extends Model
{
    protected $table = 'user_news';

    protected $primaryKey = 'user_id';

    protected $maps = [
        'id' => 'user_id',
    ];

    protected $fillable = [
        'user_id', 'content', 'status', 'created_at', 'updated_at'
    ];

    public function createNews(UserNews $userNews)
    {
        $news = new self();
        $news->user_id = $userNews->getUserId();
        $news->content = $userNews->getContent();
        $news->type = $userNews->getType();
        $news->status = $userNews->getStatus();

        $news->save();
    }
}
