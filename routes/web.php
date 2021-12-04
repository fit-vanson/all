<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
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
    echo $exitCode = Artisan::call('storage:link');
});

Route::get('/', [HomeController::class, 'home'])->middleware(['auth'])->name('home');
Route::get('/topUpload', [HomeController::class, 'topUpload'])->middleware(['auth'])->name('home.topUpload');

Route::group([ "prefix" => "file", "middleware" => ["auth"]], function() {
    Route::get('/', [FileController::class, 'index'])->name('file.index');
    Route::post('/upload', [FileController::class, 'upload'])->name('file.upload');
});
Route::group([ "prefix" => "user", "middleware" => ["auth",'role:Admin']], function() {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::post('/getIndex', [UserController::class, 'getIndex'])->name('user.getIndex');
    Route::post('/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/update', [UserController::class, 'update'])->name('user.update');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::get('/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});


