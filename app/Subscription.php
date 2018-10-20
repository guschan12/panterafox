<?php

namespace PanteraFox;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'photo_id'
    ];
}
