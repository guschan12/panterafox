<?php

namespace PanteraFox\Http\Controllers\Auth;

use PanteraFox\Avatar;
use PanteraFox\Cover;
use PanteraFox\User;
use PanteraFox\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';


    /**
     * RegisterController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'first-name' => 'required|string|max:255',
                'last-name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'country' => 'integer|min:1|max:34',
                'password' => 'required|string|min:6|confirmed',
            ],
            [
                'country.integer' => 'The country value is invalid',
                'country.min' => 'The country value is invalid',
                'country.max' => 'The country value is invalid',
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \PanteraFox\User
     */
    protected function create(array $data)
    {
        $user = new User();
        $user->first_name = $data['first-name'];
        $user->last_name = $data['last-name'];
        $user->country_id = $data['country'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->origin = 'native';
        $user->is_verified = 0;
        $user->save();

        $avatar = new Avatar();
        $avatar->source_link = '/uploads/avatars/source/default.jpg';
        $avatar->thumb_link = '/uploads/avatars/thumb/default.jpg';
        $avatar->cache_token = uniqid();
        $avatar->crop = '{"x":60,"y":60,"width":480,"height":480,"rotate":0,"scaleX":1,"scaleY":1}';

        $cover = new Cover();
        $cover->source_link = '/uploads/covers/source/default.jpg';
        $cover->thumb_link = '/uploads/covers/thumb/default.jpg';
        $cover->cache_token = uniqid();
        $cover->crop = '{"x":120.35928143712567,"y":57.99700598802394,"width":959.2814371257487,"height":259.0059880239521,"rotate":0,"scaleX":1,"scaleY":1}';

        $user->avatar()->save($avatar);
        $user->cover()->save($cover);

        return $user;
    }
}
