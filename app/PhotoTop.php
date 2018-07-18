<?php

namespace PanteraFox;

use Illuminate\Database\Eloquent\Model;

class PhotoTop extends Model
{
    /**
 * The table associated with the model.
 *
 * @var string
 */
    protected $table = 'photo_top';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'photo_id'
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

    public function photo ()
    {
        return $this->belongsTo('PanteraFox\UserPhotos');
    }
}
