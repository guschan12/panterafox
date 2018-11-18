<?php

namespace PanteraFox\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PanteraFox\Country;
use PanteraFox\Services\AvatarManager;
use PanteraFox\Services\CoverManager;
use PanteraFox\User;

class EditProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        ini_set('memory_limit', '512M');
    }

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

//        dd($userProfile->gender);

        return view('edit-profile',[
            'userProfile' => $userProfile,
            'countries' => Country::all()
        ]);
    }

    public function avatar(Request $request, AvatarManager $avatarManager)
    {
        if ($request->hasFile('photo'))
        {
           $avatarManager->updatePhoto($request->file('photo'), json_decode($request->post('crop'), true));
        }
        else
        {
            $avatarManager->updateCrop(json_decode($request->post('crop'), true));
        }

        return redirect()->back()->with('success', 'avatar');
    }

    public function cover(Request $request, CoverManager $coverManager)
    {
        if ($request->hasFile('cover_photo'))
        {
            $coverManager->updatePhoto($request->file('cover_photo'), json_decode($request->post('cover_crop'), true));
        }
        else
        {
            $coverManager->updateCrop(json_decode($request->post('cover_crop'), true));
        }

        return redirect()->back()->with('success', 'cover');
    }

    public function profile (Request $request)
    {
        $validation = $this->profileValidator($request->post());
        if($validation instanceof RedirectResponse )
        {
            $validation->getSession()->put('error_type','profile');
            return $validation;
        }

        $user = User::where('id', Auth::user()->id)->first();
        $user->first_name = $request->post('first-name');
        $user->last_name = $request->post('last-name');
        $user->country_id = $request->post('country');
        $user->birthday = $request->post('dob');
        if($request->post('gender'))
        {
            $user->gender = $request->post('gender');
        }
        $user->save();

        return redirect()->back()->with('success', 'profile');
    }

    public function password (Request $request)
    {
        $validation = $this->passwordValidator($request->post());
        if($validation instanceof RedirectResponse )
        {
            $validation->getSession()->put('error_type','password');
            return $validation;
        }
        $user = User::where('id', Auth::user()->id)->first();
        $user->password = bcrypt($request->post('password'));
        $user->save();

        return redirect()->back()->with('success', 'password');
    }

    private function profileValidator($data)
    {
        $validator =  Validator::make(
            $data,
            [
                'first-name' => 'required|string|max:255|min:2',
                'last-name' => 'required|string|max:255',
                'country' => 'required|integer|min:1|max:34',
            ],
            [
                'country.integer' => 'The country value is invalid',
                'country.min' => 'The country value is invalid',
                'country.max' => 'The country value is invalid',
            ]
        );
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    private function passwordValidator ($data)
    {
        $validator =  Validator::make(
            $data,
            [
                'password' => 'required|string|min:6|confirmed',
            ]
        );

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

}
