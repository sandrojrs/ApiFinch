<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [ApiController::class, 'authenticate']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::group(['middleware' => ['role:manager']], function () {
        Route::post('register', [ApiController::class, 'register']);
        Route::apiResource('users', UserController::class);    
        Route::apiResource('projects', ProjectController::class);
        Route::apiResource('roles', RoleController::class);
    });
    Route::group(['middleware' => ['role:executor|manager']], function () {
        Route::apiResource('tasks', TaskController::class);
    });   
    Route::get('logout', [ApiController::class, 'logout']);
});

