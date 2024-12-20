<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LabelController;;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\WebServiceController;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['controller' => TodoListController::class], function () {
        Route::apiResource('todo-list', TodoListController::class);
    });
    
    Route::group(['controller' => TasksController::class], function() {
        Route::apiResource('todo-list.tasks', TasksController::class)
            ->except('show')
            ->shallow();
    });

    Route::group(['controller' => LabelController::class], function () {
        Route::apiResource('label', LabelController::class);
    });

    Route::group(['controller' => WebServiceController::class], function () {
        Route::get('/web-service/connect/{web_service}', 'connect')->name('web-service.connect');
        Route::post('/web-service/callback', 'callback')->name('web-service.callback');
        Route::post('/web-service/{web_service}', 'store')->name('web-service.store');
    });
});
Route::group(['controller' => LoginController::class], function() {
    Route::post('login', LoginController::class)
        ->name('user.login');
});

Route::group(['controller' => RegisterController::class], function() {
    Route::post('register', RegisterController::class)
        ->name('user.register');
});
