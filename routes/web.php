<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TestsEnrollmentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('api')->as('api.')->group(function () {
    // login 
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    // check token
    Route::middleware('auth:sanctum')->group(function () {
        
        //logout
        Route::post('/logout', [AuthController::class, 'logout']);
        // check admin
        Route::group(['middleware' => 'admin'], function () {
            // users
            Route::get('/users', [UserController::class, 'index']);
            // crud
            Route::resource('/user',UserController::class)->only([
                'show', 'store', 'update', 'destroy',
            ]);
        });
        // mail notification 
        Route::get('/send-testenrollment', [TestsEnrollmentController::class, 'sendTestNotification']);

        // download item
        Route::get('/downloads/{id}', [PostController::class, 'download'])->name('download'); // download an image

        // download all item
        Route::get('/downloads', [PostController::class, 'downloads'])->name('downloadAll'); // download all image of...

        // posts
        Route::resource('/posts', PostController::class);
    });
});
