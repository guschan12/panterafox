<?php

namespace PanteraFox;

use Illuminate\Database\Eloquent\Model;

class Cover extends Model
{

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'source_link', 'thumb_link','cache_token','cover'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo('PanteraFox\User');
    }
}