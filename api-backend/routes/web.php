<?php

use App\Http\Controllers\Auth\SocialLoginController;
use Illuminate\Support\Facades\Route;

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
Route::group(['namespace' => 'Auth'], function () {
    Route::get('login/{provider?}', [SocialLoginController::class, 'login'])->name('auth.socialite');
    Route::get('login/{provider}/callback', [SocialLoginController::class, 'callback']);
    Route::get('logout', [SocialLoginController::class, 'logout']);
    Route::get('refresh/{provider?}', [SocialLoginController::class, 'refresh']);
});
