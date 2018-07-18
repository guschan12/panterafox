<?php

namespace PanteraFox\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PanteraFox\Country;
use PanteraFox\Services\PhotoManager;
use PanteraFox\User;
use PanteraFox\UserPhotos;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //TODO: move this logic to separate service
        //EXAMPLE: $userProfile = userManager->getProfileById(Auth::user()->id)
        $userProfile = User::where('id', Auth::user()->id)
            ->with(['userPhotos' => function ($query) {
                $query->limit(8)->orderBy('id', 'desc')
                    ->withCount('top')
                    ->with(['top' => function ($query) {
                        $query->select('photo_id')->where('user_id', Auth::user()->id);
                    }]);
            }])
            ->with('avatar')
            ->with('cover')
            ->first();

        $countPhotos = UserPhotos::where('user_id', Auth::user()->id)->count();
        return view('home', [
            'userProfile' => $userProfile,
            'countPhotos' => $countPhotos
        ]);
    }

    public function postIndex(Request $request, PhotoManager $photoManager)
    {
        if ($request->hasFile('photo')) {
            if ($photoManager->addPhoto($request->file('photo'))) {
                return new JsonResponse([
                    'success' => true
                ]);
            }

        }
    }
}
