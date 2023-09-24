<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\TaskFileController;/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('signup', [AuthController::class,'signup']);
Route::post('login', [AuthController::class,'login']);
Route::post('logout', [AuthController::class,'logout'])->middleware('auth:sanctum');

Route::get('home', [TaskController::class,'home'])->middleware('auth:sanctum');
Route::get('tasks/index', [TaskController::class,'index'])->middleware('auth:sanctum');
Route::post('tasks/store', [TaskController::class,'store'])->middleware('auth:sanctum');
Route::post('tasks/{id}/update', [TaskController::class,'update'])->middleware('auth:sanctum');
Route::post('tasks/{id}/destroy', [TaskController::class,'destroy'])->middleware('auth:sanctum');

Route::get('roles/index', [RoleController::class,'index'])->middleware('auth:sanctum');
Route::post('roles/store', [RoleController::class,'store'])->middleware('auth:sanctum');
Route::post('roles/{id}/update', [RoleController::class,'update'])->middleware('auth:sanctum');
Route::post('roles/{id}/destroy', [RoleController::class,'destroy'])->middleware('auth:sanctum');

Route::get('users/index', [UserController::class,'index'])->middleware('auth:sanctum');
Route::post('users/{id}/update', [UserController::class,'updateRole'])->middleware('auth:sanctum');

Route::post('tasks/{task_id}/storefile', [TaskFileController::class,'store'])->middleware('auth:sanctum');
Route::post('tasks/{task_id}/destroyfile/{id}', [TaskFileController::class,'destroy'])->middleware('auth:sanctum');