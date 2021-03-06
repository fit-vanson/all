<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ApiV3Controller;
use App\Http\Controllers\Api\ApiV4Controller;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\WallpaperController;
use App\Http\Controllers\Api\ApiV2Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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
if (App::environment('production', 'staging')) {
    URL::forceScheme('https');
}

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test-api',function (){
   return ['a'=>'sssssdfsdf'];
});

Route::group([
//    'middleware' => 'auth.apikey'
], function() {
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category_id}/wallpapers', [CategoryController::class, 'getWallpapers']);

    Route::get('/wallpaper-detail/{id}/{device_id}', [WallpaperController::class, 'show']);
    Route::get('/wallpapers/featured', [WallpaperController::class, 'getFeatured']);
    Route::get('/wallpapers/popular', [WallpaperController::class, 'getPopulared']);
    Route::get('/wallpapers/newest', [WallpaperController::class, 'getNewest']);

    Route::post('/wallpaper-favorite', [FavoriteController::class, 'likeWallpaper']);
    Route::post('/wallpaper-favorite-unsaved', [FavoriteController::class, 'disLikeWallpaper']);
    Route::get('/favorite/{device_id}', [FavoriteController::class, 'getSaved']);
});

Route::get('/', [ApiController::class, 'index']);

Route::group([
    "prefix" => "v1"
//    'middleware' => 'auth.apikey'
], function() {
    Route::get('/get_categories',[ApiController::class, 'get_categories']);
    Route::get('/get_wallpapers',[ApiController::class, 'get_wallpapers']);
    Route::get('/get_category_details',[ApiController::class, 'get_category_details']);
    Route::get('/get_ads',[ApiController::class, 'get_ads']);
    Route::get('/get_settings',[ApiController::class, 'get_settings']);
    Route::post('/update_view',[ApiController::class, 'update_view']);
    Route::post('/update_download',[ApiController::class, 'update_download']);

    Route::get('/',[ApiController::class, 'index']);
});


Route::group([
    "prefix" => "v2"
//    'middleware' => 'auth.apikey'
], function() {
    Route::get('/',[ApiV2Controller::class, 'index']);
    Route::post('/getData',[ApiV2Controller::class, 'getData']);
});


Route::group([
    "prefix" => "v3",
//    'middleware' => 'auth.apikey'
], function() {
    Route::get('version/check/{id}',[ApiV3Controller::class, 'checkCode']);
    Route::get('/',[ApiV3Controller::class, 'index']);
    Route::get('first',[ApiV3Controller::class, 'first']);

    Route::get('category/all',[ApiV3Controller::class, 'categoryAll']);
    Route::get('wallpaper/all/{order}/{page}',[ApiV3Controller::class, 'wallpapersAll']);
    Route::get('wallpaper/random/{page}',[ApiV3Controller::class, 'wallpapersRandom']);
    Route::get('wallpaper/category/{page}/{category}',[ApiV3Controller::class, 'wallpapersByCategory']);
    Route::get('wallpaper/query/{page}/{query}',[ApiV3Controller::class, 'wallpapersBysearch']);
    Route::get('wallpaper/add/set/{id}',[ApiV3Controller::class, 'api_add_set']);
    Route::get('wallpaper/add/view/{id}',[ApiV3Controller::class, 'api_add_view']);
    Route::get('wallpaper/add/download/{id}',[ApiV3Controller::class, 'api_add_download']);

});

Route::group([
    "prefix" => "v4",
//    'middleware' => 'auth.apikey'
], function() {
    Route::get('admob',[ApiV4Controller::class, 'admob']);
    Route::get('settings',[ApiV4Controller::class, 'settings']);
    Route::get('home',[ApiV4Controller::class, 'home']);

    Route::get('categories',[ApiV4Controller::class, 'categories']);

    Route::get('wallpaper',[ApiV4Controller::class, 'wallpaper']);
    Route::get('wallpaper/popular',[ApiV4Controller::class, 'popular']);
    Route::get('wallpaper/download',[ApiV4Controller::class, 'download']);
    Route::get('wallpaper/random',[ApiV4Controller::class, 'random']);
    Route::get('wallpaper/cid',[ApiV4Controller::class, 'cid']);
    Route::get('wallpaper/live',[ApiV4Controller::class, 'live']);

    Route::get('wallpaper/hashtag',[ApiV4Controller::class, 'hashtag']);



    Route::get('add/show/wallpaper',[ApiV4Controller::class, 'viewWallpaper']);



});

