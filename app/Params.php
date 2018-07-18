<?php

namespace PanteraFox;

use Illuminate\Database\Eloquent\Model;

class Params extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'top_views'
    ];
}
