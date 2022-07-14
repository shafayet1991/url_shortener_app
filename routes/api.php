<?php

use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\UrlShortenerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');


Route::group(['prefix' => 'urls', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/shorten', [UrlShortenerController::class, 'store']);
    Route::get('/getUrl', [UrlShortenerController::class, 'getUrlList']);
});
Route::post('/SUrl/shorten', [UrlShortenerController::class, 'urlHandler']);

