<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController as UserController;
use App\Http\Controllers\AuthController as AuthController;

Route::post('/auth', [AuthController::class, 'createToken'])->name('auth.createToken');
Route::delete('/auth', [AuthController::class, 'deleteToken'])->middleware('auth:sanctum')->name('auth.deleteToken');

Route::post('/users', [UserController::class, 'register'])->name('user.register');

// 회원 리스트 조회. 관리자만 호출 가능.
// Route::get('/users', [UserController::class, 'register'])->middleware('auth:sanctum')->name('user.register');
// 회원 탈퇴. 관리자만 호출 가능.
// Route::delete('/users', [UserController::class, 'register'])->middleware('auth:sanctum')->name('user.register');

Route::get('/users/me', [UserController::class, 'retriveMe'])->middleware('auth:sanctum')->name('user.me');
Route::get('/users/{id}', [UserController::class, 'retriveBasic'])->middleware('auth:sanctum')->name('user.retrive');
Route::put('/users/{id}', [UserController::class, 'update'])->middleware('auth:sanctum')->name('user.update');
Route::delete('/users/{id}', [UserController::class, 'setStateDelete'])->middleware('auth:sanctum')->name('user.delete');

Route::post('/users/{id}/company', [UserController::class, 'registerCompany'])->middleware('auth:sanctum')->name('user.registerCompany');


// 회원가입 후 이메일 인증
Route::get('/auth/verity/{token}', [AuthController::class, 'verityEmail'])->name('auth.verity');





Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/auth/kakao', function (Request $request) {
    return "OK";
});


Route::get('/aasdasds', function () {
    return view('welcome');
});

Route::middleware('auth:sanctum')->group( function () {
    // Route::resource('products', ProductController::class);

    // Route::delete('/users/{id}', [UserController::class, 'testDeleteUser'])->name('test.user.delete');
});



Route::delete('/users/{id}', [UserController::class, 'testDeleteUser'])->name('test.user.delete');