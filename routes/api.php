<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::post('makeAdmin', [UserController::class, 'makeAdmin'])->middleware('auth:sanctum');
Route::get('getUsers', [UserController::class, 'getUsers'])->middleware('auth:sanctum');
Route::get('getAdmins', [UserController::class, 'getAdmins'])->middleware('auth:sanctum');
Route::post('deleteAdmin', [UserController::class, 'deleteAdmin'])->middleware('auth:sanctum');
Route::post('changePassword', [UserController::class, 'changePassword'])->middleware('auth:sanctum');




// gallery routes
Route::group(['prefix' => 'gallery', 'as' => 'gallery.'], function () {
    Route::get('index', [GalleryController::class, 'index']);
    Route::post('store', [GalleryController::class, 'store'])->middleware('auth:sanctum');
    Route::get('destroy/{id}', [GalleryController::class, 'destroy'])->middleware('auth:sanctum');
});

// Category routes
Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
    Route::get('index', [CategoryController::class, 'index'])->middleware('CheckLangApi');
    Route::post('store', [CategoryController::class, 'store'])->middleware('auth:sanctum');
    Route::post('update/{id}', [CategoryController::class, 'update'])->middleware('auth:sanctum');
    Route::get('destroy/{id}', [CategoryController::class, 'destroy'])->middleware('auth:sanctum');
});


// Menu routes
Route::group(['prefix' => 'menu', 'as' => 'menu.'], function () {
    Route::get('index', [MenuController::class, 'index'])->middleware('CheckLangApi');
    Route::get('getMenuById/{id}', [MenuController::class, 'getMenuById'])->middleware('CheckLangApi');
    Route::post('store', [MenuController::class, 'store'])->middleware('auth:sanctum');
    Route::post('update/{id}', [MenuController::class, 'update'])->middleware('auth:sanctum');
    Route::get('destroy/{id}', [MenuController::class, 'destroy'])->middleware('auth:sanctum');
});
