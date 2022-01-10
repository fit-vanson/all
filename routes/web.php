<?php

use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\BlockIPController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeatureImagesController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KpopWallpapersController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WallpapersController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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
if (App::environment('production', 'staging')) {
    URL::forceScheme('https');
}
require __DIR__.'/auth.php';
Route::get('/clear-cache',function (){
    echo  Artisan::call('optimize:clear');
});
Route::get('/link',function (){
    Artisan::call('storage:link');
});
Route::get('routes', function () {
    $routeCollection = Route::getRoutes();

    echo "<table style='width:100%'>";
    echo "<tr>";
    echo "<td width='10%'><h4>HTTP Method</h4></td>";
    echo "<td width='10%'><h4>Route</h4></td>";
    echo "<td width='10%'><h4>Name</h4></td>";
    echo "<td width='70%'><h4>Corresponding Action</h4></td>";
    echo "</tr>";
    foreach ($routeCollection as $value) {
        echo "<tr>";
        echo "<td>" . $value->methods()[0] . "</td>";
        echo "<td>" . $value->uri() . "</td>";
        echo "<td>" . $value->getName() . "</td>";
        echo "<td>" . $value->getActionName() . "</td>";
        echo "</tr>";
    }
    echo "</table>";
});
Route::get('/phpinfo',function (){
    echo phpinfo();
});

Route::get('test', function (){
    $get_url = 'http://127.0.0.1:8000/api/categories';
//    dd($get_url);
    $ch = curl_init($get_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Authorization: J6YZ4712Fv3yAyXFohHiLPWQHytB3pJjobEpCSEJD3rWbFLuYem65Y5N9WTdRWna'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    dd($result);
});

Route::get('/updateapp', function()
{
    Artisan::call('dump-autoload');
    echo 'dump-autoload complete';
});


Route::get('/', [HomeController::class, 'show'])->name('show');
Route::get('/policy', [HomeController::class, 'policy'])->name('policy');
Route::group([ "prefix" => "admin", "middleware" => ["auth"]], function() {
    Route::get('/', [HomeController::class, 'home'])->middleware(['auth'])->name('home');
    Route::get('/file', [HomeController::class, 'file'])->name('home.file');
    Route::group([ "prefix" => "home", "middleware" => ["auth"]], function() {
        Route::get('/', [HomeController::class, 'index'])->name('home.index');
//        Route::post('/getIndex', [HomeController::class, 'getIndex'])->name('home.getIndex');
//        Route::post('/create', [HomeController::class, 'create'])->name('home.create');
//        Route::post('/update', [HomeController::class, 'update'])->name('home.update');
//        Route::get('/{id}/edit', [HomeController::class, 'edit'])->name('home.edit');
//        Route::get('/{id}/delete', [HomeController::class, 'delete'])->name('home.delete');
    });

    Route::group([ "prefix" => "user", "middleware" => ["auth"]], function() {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::post('/getIndex', [UserController::class, 'getIndex'])->name('user.getIndex');
        Route::post('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/update', [UserController::class, 'update'])->name('user.update');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::get('/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
        Route::post('/change-info', [UserController::class, 'changeInfo'])->name('user.changeInfo');
    });
    Route::group([ "prefix" => "category", "middleware" => ["auth"]], function() {
        Route::get('/', [CategoryController::class, 'index'])->name('category.index');
        Route::post('/getIndex', [CategoryController::class, 'getIndex'])->name('category.getIndex');
        Route::post('/create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/update', [CategoryController::class, 'update'])->name('category.update');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
        Route::get('/{id}/delete', [CategoryController::class, 'delete'])->name('category.delete');
    });
    Route::group([ "prefix" => "wallpaper", "middleware" => ["auth"]], function() {
        Route::get('/', [WallpapersController::class, 'index'])->name('wallpaper.index');
        Route::post('/getIndex', [WallpapersController::class, 'getIndex'])->name('wallpaper.getIndex');
        Route::post('/create', [WallpapersController::class, 'create'])->name('wallpaper.create');
        Route::post('/update', [WallpapersController::class, 'update'])->name('wallpaper.update');
        Route::get('/{id}/edit', [WallpapersController::class, 'edit'])->name('wallpaper.edit');
        Route::get('/{id}/delete', [WallpapersController::class, 'delete'])->name('wallpaper.delete');
        Route::post('/deleteSelect', [WallpapersController::class, 'deleteSelect'])->name('wallpaper.deleteSelect');
    });
    Route::group([ "prefix" => "api-keys", "middleware" => ["auth"]], function() {
        Route::get('/', [ApiKeyController::class, 'index'])->name('api_keys.index');
        Route::post('/getIndex', [ApiKeyController::class, 'getIndex'])->name('api_keys.getIndex');
        Route::post('/create', [ApiKeyController::class, 'create'])->name('api_keys.create');
        Route::post('/update', [ApiKeyController::class, 'update'])->name('api_keys.update');
        Route::get('/{id}/edit', [ApiKeyController::class, 'edit'])->name('api_keys.edit');
        Route::get('/{id}/delete', [ApiKeyController::class, 'delete'])->name('api_keys.delete');
        Route::get('/{id}/change-status', [ApiKeyController::class, 'changeStatus'])->name('api_keys.changeStatus');
    });
    Route::group([ "prefix" => "block-ips", "middleware" => ["auth"]], function() {
        Route::get('/', [BlockIPController::class, 'index'])->name('block_ips.index');
        Route::post('/getIndex', [BlockIPController::class, 'getIndex'])->name('block_ips.getIndex');
        Route::post('/create', [BlockIPController::class, 'create'])->name('block_ips.create');
        Route::post('/update', [BlockIPController::class, 'update'])->name('block_ips.update');
        Route::get('/{id}/edit', [BlockIPController::class, 'edit'])->name('block_ips.edit');
        Route::get('/{id}/delete', [BlockIPController::class, 'delete'])->name('block_ips.delete');
    });


    Route::group([ "prefix" => "site", "middleware" => ["auth"]], function() {
        Route::get('/list', [SiteController::class, 'index'])->name('site.index');
        Route::post('/getIndex', [SiteController::class, 'getIndex'])->name('site.getIndex');
        Route::post('/create', [SiteController::class, 'create'])->name('site.create');
        Route::post('/update', [SiteController::class, 'update'])->name('site.update');
        Route::get('/{id}/edit', [SiteController::class, 'edit'])->name('site.edit');
        Route::get('/{id}/delete', [SiteController::class, 'delete'])->name('site.delete');
        Route::get('/{id}/change-ads', [SiteController::class, 'changeAds'])->name('site.changeAds');

        Route::get('view/{id}', [SiteController::class, 'site_index'])->name('site.site_index');
        Route::post('view/{id}/category', [SiteController::class, 'site_getCategory'])->name('site.getCategory');
        Route::post('view/{id}/add-category', [SiteController::class, 'site_addCategory'])->name('site.addCategory');
        Route::post('view/{id}/update-category', [SiteController::class, 'site_updateCategory'])->name('site.site_updateCategory');
        Route::get('view/{id}/category/{id1}/edit', [SiteController::class, 'site_editCategory'])->name('site.editCategory');
        Route::get('view/{id}/category/edit', [SiteController::class, 'site_editAddCategory'])->name('site.editAddCategory');

        Route::get('view/{id}/block-ips', [SiteController::class, 'site_BlockIps'])->name('site.BlockIps');
        Route::post('view/{id}/block-ips/get', [SiteController::class, 'site_getBlockIps'])->name('site.getBlockIps');
        Route::post('view/{id}/block-ips/update-block-ips', [SiteController::class, 'site_updateBlockIp'])->name('site.site_updateBlockIp');
        Route::get('view/{id}/block-ips/edit', [SiteController::class, 'site_editBlockIp'])->name('site.editBlockIp');
        Route::get('view/{id}/block-ips/{id1}/delete', [SiteController::class, 'site_deleteBlockIp'])->name('site.deleteBlockIp');

        Route::get('view/{id}/home', [SiteController::class, 'site_Home'])->name('site.home');
        Route::post('view/{id}/home/update', [SiteController::class, 'site_updateHome'])->name('site.site_updateHome');

        Route::get('view/{id}/policy', [SiteController::class, 'site_Policy'])->name('site.policy');
        Route::post('view/{id}/policy/update', [SiteController::class, 'site_updatePolicy'])->name('site.site_updatePolicy');


        Route::get('view/{id}/feature-images', [FeatureImagesController::class, 'index'])->name('site.FeatureImages');
        Route::post('view/{id}/feature-images/get', [FeatureImagesController::class, 'getIndex'])->name('site.getFeatureImages');
        Route::post('view/{id}/feature-images/create', [FeatureImagesController::class, 'create'])->name('site.site_createFeatureImages');
        Route::post('view/{id}/feature-images/update', [FeatureImagesController::class, 'update'])->name('site.site_updateFeatureImages');
        Route::get('view/{id}/feature-images/{id_image}/edit', [FeatureImagesController::class, 'edit'])->name('site.editFeatureImages');
        Route::get('view/{id}/feature-images/{id1}/delete', [FeatureImagesController::class, 'delete'])->name('site.deleteFeatureImages');

        Route::get('view/{id}/load-feature', [SiteController::class, 'site_LoadFeature'])->name('site.LoadFeature');
        Route::get('view/{id}/load-feature/update', [SiteController::class, 'site_updateLoadFeature'])->name('site.site_updateLoadFeature');
    });









});





Route::group([ "prefix" => "kpopwallpapers.net", "middleware" => ["auth"]], function() {
    Route::group([ "prefix" => "category", "middleware" => ["auth"]], function() {
        Route::get('/', [KpopWallpapersController::class, 'category'])->name('kpopwallpapers.category.index');
        Route::post('/getIndex', [KpopWallpapersController::class, 'getIndex'])->name('kpopwallpapers.category.getIndex');
        Route::post('/create', [KpopWallpapersController::class, 'create'])->name('kpopwallpapers.category.create');
        Route::post('/update', [KpopWallpapersController::class, 'update'])->name('kpopwallpapers.category.update');
        Route::get('/edit/{id}', [KpopWallpapersController::class, 'edit'])->name('kpopwallpapers.category.edit');
        Route::get('/delete/{id}', [KpopWallpapersController::class, 'delete'])->name('kpopwallpapers.category.delete');
        //    Route::get('/info', [UserController::class, 'infoUser'])->name('user.info');
//        Route::post('/change-info', [KpopWallpapersController::class, 'changeInfo'])->name('user.changeInfo');
    });
});




