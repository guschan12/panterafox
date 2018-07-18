<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'AppController@index');

Auth::routes();

Route::get('login/facebook', 'Auth\LoginController@redirectToFacebook');
Route::get('login/facebook/callback', 'Auth\LoginController@handleFacebookCallback');

//Route::get('/home', 'HomeController@index')->name('home');
Route::post('/home', 'HomeController@postIndex')->name('home-post');


Route::post('/profile/user/photo', 'UserController@userPhoto');
Route::post('/profile/user/video/{action}', 'UserController@video');


Route::get('/home/settings', 'EditProfileController@index')->name('edit-profile');
Route::post('/home/settings/avatar', 'EditProfileController@avatar')->name('save-avatar');
Route::post('/home/settings/cover', 'EditProfileController@cover')->name('save-cover');
Route::post('/home/settings/profile', 'EditProfileController@profile')->name('save-profile');
Route::post('/home/settings/password', 'EditProfileController@password')->name('save-password');

Route::get('/home/settings', 'EditProfileController@index')->name('edit-profile');

Route::get('/profile/{id}', 'ProfileController@index')->name('profile');

Route::get('/home', function() {
    return redirect()->route('profile', ['id' => Auth::user()->id]);
})->name('home');



Route::get('/country', 'CountryController@index')->name('countries');
Route::get('/country/{name}', function($name)
{
    return redirect()->route('country', ['name' => $name, 'content' => 'photo']);
});
Route::get('/country/{name}/{content}', 'CountryController@content')->name('country');
Route::post('/country/{name}/{content}/loadmore', 'CountryController@loadMore');

Route::get('/world/{content}','CountryController@world');
