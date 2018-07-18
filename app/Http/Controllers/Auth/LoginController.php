<?php

namespace PanteraFox\Http\Controllers\Auth;

use PanteraFox\Cover;
use PanteraFox\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use PanteraFox\Services\CountryManager;
use PanteraFox\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    const facebookScope = [
        'user_birthday',
        'user_location',
    ];
    /**
     * Initialize Facebook fields to override
     */
    const facebookFields = [
        'name', // Default
        'email', // Default
        'gender', // Default
        'birthday', // I've given permission
        'location', // I've given permission
    ];

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')
            ->fields(self::facebookFields)
            ->scopes(self::facebookScope)
            ->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @param CountryManager $countryManager
     * @return \Illuminate\Http\Response
     */
    public function handleFacebookCallback(CountryManager $countryManager)
    {
        $socialUser = Socialite::driver('facebook')->fields(self::facebookFields)->user();

        $findUser = User::where('email',$socialUser->email)->first();

        if($findUser)
        {
            Auth::login($findUser);

            return redirect($this->redirectTo);
        }
        else
        {
            $is_verified = json_decode(file_get_contents("https://graph.facebook.com/v2.1/$socialUser->id?fields=is_verified&access_token=$socialUser->token"))->is_verified;
            $name = explode(' ',$socialUser->name);
            $countryName = 'Ukraine';
            if(is_array($socialUser->user['location']))
            {
                $user_country = explode(',', $socialUser->user['location']['name']);
                $countryName = trim($user_country[1]);
            }
            $countryId = $countryManager->getCountryIdByName($countryName);

            $user = new User;
            $user->first_name = $name[0];
            $user->last_name = $name[1];
            $user->email = $socialUser->email;
            $user->password = '';
            $user->country_id = $countryId;
            $user->birthday = $socialUser->user['birthday'];
            $user->gender = $socialUser->user['gender'];
            $user->avatar = $socialUser->avatar;
            $user->avatar_original = $socialUser->avatar_original;
            $user->origin = 'facebook';
            $user->is_verified = $is_verified;
            $user->save();

            $cover = new Cover();
            $cover->source_link = '/uploads/covers/source/default.jpg';
            $cover->thumb_link = '/uploads/covers/thumb/default.jpg';
            $cover->cache_token = uniqid();
            $cover->crop = '{"x":120.35928143712567,"y":57.99700598802394,"width":959.2814371257487,"height":259.0059880239521,"rotate":0,"scaleX":1,"scaleY":1}';
            $user->cover()->save($cover);

            $lastUser = User::where('email',$socialUser->email)->first();

            Auth::login($lastUser);

            return redirect($this->redirectTo);
        }
    }

    private function getCountryIdByName($countryName)
    {

    }
}
