<?php

namespace PanteraFox;

use Illuminate\Database\Eloquent\Model;

class UserVideo extends Model
{
    protected $fillable = [
        'user_id', 'link', 'video_id','views'
    ];

    public function user()
    {
        return $this->belongsTo('PanteraFox\User');
    }
}
