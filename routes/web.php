<?php

use App\Http\Controllers\TaskController; 
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home'); // name ditambahkan

Route::get('/tasks/', [TaskController::class, 'index'])->name('tasks.index'); // name ditambahkan