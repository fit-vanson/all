<?php

use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\BlockIPController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KpopWallpapersController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WallpapersController;
use Illuminate\Support\Facades\Artisan;
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

Route::get('/updateapp', function()
{
    Artisan::call('dump-autoload');
    echo 'dump-autoload complete';
});
Route::get('/', [HomeController::class, 'home'])->middleware(['auth'])->name('home');



Route::group([ "prefix" => "user", "middleware" => ["auth"]], function() {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::post('/getIndex', [UserController::class, 'getIndex'])->name('user.getIndex');
    Route::post('/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/update', [UserController::class, 'update'])->name('user.update');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::get('/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
//    Route::get('/info', [UserController::class, 'infoUser'])->name('user.info');
    Route::post('/change-info', [UserController::class, 'changeInfo'])->name('user.changeInfo');
});
Route::group([ "prefix" => "site", "middleware" => ["auth"]], function() {
    Route::get('/', [SiteController::class, 'index'])->name('site.index');
    Route::post('/getIndex', [SiteController::class, 'getIndex'])->name('site.getIndex');
    Route::post('/create', [SiteController::class, 'create'])->name('site.create');
    Route::post('/update', [SiteController::class, 'update'])->name('site.update');
    Route::get('/{id}/edit', [SiteController::class, 'edit'])->name('site.edit');
    Route::get('/{id}/delete', [SiteController::class, 'delete'])->name('site.delete');
    Route::get('/{id}', [SiteController::class, 'site_index'])->name('site.site_index');
    Route::post('{id}/category', [SiteController::class, 'site_getCategory'])->name('site.getCategory');
    Route::post('{id}/wallpaper', [SiteController::class, 'site_getWallpaper'])->name('site.getWallpaper');
    Route::post('{id}/block-ips', [SiteController::class, 'site_getBlockIps'])->name('site.getBlockIps');
//    Route::post('{id}/add-category', [SiteController::class, 'site_addCategory'])->name('site.addCategory');
    Route::post('{id}/update-category', [SiteController::class, 'site_updateCategory'])->name('site.site_updateCategory');
    Route::get('/{id}/category/{id1}/edit', [SiteController::class, 'site_editCategory'])->name('site.editCategory');

    Route::get('/{id}/block-ips/edit', [SiteController::class, 'site_editBlockIp'])->name('site.editBlockIp');
    Route::get('/{id}/block-ips/{id1}/delete', [SiteController::class, 'site_deleteBlockIp'])->name('site.deleteBlockIp');
    Route::post('{id}/update-block-ips', [SiteController::class, 'site_updateBlockIp'])->name('site.site_updateBlockIp');
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
});

Route::group([ "prefix" => "block-ips", "middleware" => ["auth"]], function() {
    Route::get('/', [BlockIPController::class, 'index'])->name('block_ips.index');
    Route::post('/getIndex', [BlockIPController::class, 'getIndex'])->name('block_ips.getIndex');
    Route::post('/create', [BlockIPController::class, 'create'])->name('block_ips.create');
    Route::post('/update', [BlockIPController::class, 'update'])->name('block_ips.update');
    Route::get('/{id}/edit', [BlockIPController::class, 'edit'])->name('block_ips.edit');
    Route::get('/{id}/delete', [BlockIPController::class, 'delete'])->name('block_ips.delete');
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
        Route::post('/change-info', [KpopWallpapersController::class, 'changeInfo'])->name('user.changeInfo');
    });
});




