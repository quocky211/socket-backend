<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TestsEnrollmentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Broadcast;
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

    Route::resource('/posts', PostController::class);
    //
    Route::get('/', function(){
        return view('chat');
    });
    // login 
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    // check token
    Route::middleware('auth:sanctum')->group(function () {

        //logout
        Route::post('/logout', [AuthController::class, 'logout']);
        // check admin
        Route::group(['middleware' => 'admin'], function () {
            // crud
            Route::resource('/user', UserController::class)->only([
                'show', 'store', 'update', 'destroy',
            ]);
        });
        //users
        Route::get('/users', [UserController::class, 'index']);
        // api message
        Route::resource('/message',ChatController::class)->only([
            'show', 'store', 'destroy',
        ]);

        Route::get('/conversationId/{id}',[ChatController::class,'getConversation']);
        // mail notification 
        Route::get('/send-testenrollment', [TestsEnrollmentController::class, 'sendTestNotification']);

        // download item
        Route::get('/downloads/{id}', [PostController::class, 'download'])->name('download'); // download an image

        // download all item
        Route::get('/downloads', [PostController::class, 'downloads'])->name('downloadAll'); // download all image of...

        // posts
    });
});
