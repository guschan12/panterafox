<?php

namespace PanteraFox;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'country_id', 'password','birthday', 'gender', 'avatar', 'avatar_original','origin','is_verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function userPhotos()
    {
        return $this->hasMany('PanteraFox\UserPhotos');
    }

    public function userVideos()
    {
        return $this->hasMany('PanteraFox\UserVideo');
    }

    public function avatar()
    {
        return $this->hasOne('PanteraFox\Avatar');
    }

    public function cover()
    {
        return $this->hasOne('PanteraFox\Cover');
    }

    public function photoTop()
    {
        return $this->hasMany('PanteraFox\PhotoTop');
    }
}
