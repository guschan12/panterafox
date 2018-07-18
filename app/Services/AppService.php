<?php

namespace PanteraFox\Services;

use PanteraFox\Params;
use PanteraFox\UserVideo;

class AppService
{
    public static function updateTopViews()
    {
        $topViews = UserVideo::orderBy('views', 'desc')->first();
        if($topViews)
        {
            $params = Params::find(1);
            $params->top_views = $topViews['views'];
            $params->update();
        }

    }
}
