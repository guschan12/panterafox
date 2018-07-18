<?php

namespace PanteraFox\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PanteraFox\Services\PhotoManager;
use PanteraFox\Services\VideoManager;
use PanteraFox\User;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function userPhoto(Request $request, PhotoManager $photoManager)
    {
        $action = $request->post('action');
        switch ($action)
        {
            case 'rotate' :
                $photoManager->rotate($request->post('id'), $request->post('direction'));
                $sourceUrl = $photoManager->getSourceById($request->post('id'));
                return response(json_encode([
                    'success' => true,
                    'source_link' => $sourceUrl
                ]), 200);
            case 'remove' :
                $photoManager->removeById($request->post('id'));
                $photoData = $request->post('offset') ? $photoManager->getOneUserPhotoByOffset($request->post('offset')) : false;
                return json_encode([
                    'success' => true,
                    'photoData' => $photoData
                ]);
            case 'loadmore' :
                $photos = $photoManager->loadMore($request->post('user_id'), $request->post('offset'));
                return json_encode([
                    'success' => true,
                    'photos'  => $photos
                ]);
            case 'top-down' :
                if($photoManager->topDown($request->post('id')))
                {
                    return json_encode(['success'=>true]);
                }
            case 'top-up' :
                if(!isset(Auth::user()->id))
                {
                    return abort(401);
                }
                if($photoManager->topUp($request->post('id')))
                {
                    return json_encode(['success'=>true]);
                }
        }
    }

    public function video(Request $request, VideoManager $videoManager, $action)
    {
        switch ($action)
        {
            case 'add' :
                $response = $videoManager->add($request->post('video_link'));
                return new JsonResponse($response);
            break;
            case 'view' :
                $response = $videoManager->view($request->post('id'));
                return new JsonResponse($response);
                break;
            case 'remove' :
                $response = $videoManager->remove($request->post('id'));
                return new JsonResponse($response);
                break;
            case 'loadmore' :
                $response = $videoManager->loadmore($request->post('user_id'), $request->post('offset'));
                return new JsonResponse($response);
                break;
        }
    }
}
