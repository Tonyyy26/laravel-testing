<?php

use App\Http\Controllers\TodoListController;
use Illuminate\Http\Request;
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

Route::group(['controller' => TodoListController::class], function () {
    Route::get('/todo-list', 'index')->name('todo-list.index');
    Route::get('/todo-list/{list}', 'show')->name('todo-list.show');
    Route::post('/todo-list', 'store')->name('todo-list.store');
});

