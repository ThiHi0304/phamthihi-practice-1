<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PhoneController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/posts', [PostController::class,'index']);
Route::get('/posts/{id}', [PostController::class,'show']);
Route::post('/posts', [PostController::class,'store']);
Route::delete('/posts/{id}', [PostController::class,'destroy']);
Route::put('/posts/{id}', [PostController::class,'update']);


Route::get('/users', [UserController::class,'index']);
Route::get('/users/{id}', [UserController::class,'show']);
Route::post('/users', [UserController::class,'store']);
Route::delete('/users/{id}', [UserController::class,'destroy']);
Route::put('/users/{id}', [UserController::class,'update']);

Route::get('/phones', [PhoneController::class, 'index']);
Route::get('/phones/{id}', [PhoneController::class, 'show']);
Route::post('/phones', [PhoneController::class, 'store']);
Route::delete('/phones/{id}', [PhoneController::class, 'destroy']);
Route::put('/phones/{id}', [PhoneController::class, 'update']);