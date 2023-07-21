<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController; 
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home'); 



Route::prefix('tasks')
    ->name('tasks.')
    ->controller(TaskController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create/{status?}', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('{id}/edit', 'edit')->name('edit');
        Route::put('{id}/edit', 'update')->name('update');
        Route::get('{id}/delete', 'delete')->name('delete');
        Route::delete('{id}/delete', 'destroy')->name('destroy');
        Route::get('progress', 'progress')->name('progress');
        Route::patch('{id}/move', 'move')->name('move');
        Route::patch('{id}/check', 'moveToTaskList')->name('moveToTaskList');  
    });

 Route::name('auth.')
    ->controller(AuthController::class)
    ->group(function () {
        Route::get('signup', 'signupForm')->name('signupForm');
        Route::post('signup', 'signup')->name('signup');
    });