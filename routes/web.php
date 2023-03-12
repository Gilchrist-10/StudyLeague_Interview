<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\RegisterUserDataController;

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

Route::get('/', function () {
    return view('welcome');
});


 
Route::get('/register', [RegisterUserController::class, 'registerUserView']);
Route::get('/refresh_captcha', [RegisterUserController::class, 'refreshCaptcha']);
Route::get('/updatePromotion', [RegisterUserController::class, 'updatePromotion']);
Route::post('/registerUserAccount', [RegisterUserController::class, 'registerUserAccount']);
Route::get('/registerUserAccountData', [RegisterUserDataController::class, 'registerUserAccountData'])->name('registerUserAccountData');