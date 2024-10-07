<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController as UserController;
use App\Http\Controllers\AuthController as AuthController;

// 인증 토큰 생성(로그인) 
Route::post('/auth', [AuthController::class, 'createToken'])->name('auth.createToken');

// 인증 토큰 삭제(로그아웃) 
Route::delete('/auth', [AuthController::class, 'deleteToken'])->middleware('auth:sanctum')->name('auth.deleteToken');

// 유저 생성(회원가입)
Route::post('/users', [UserController::class, 'register'])->name('user.register');

// 회원 리스트 조회. 관리자만 호출 가능.
// Route::get('/users', [UserController::class, 'register'])->middleware('auth:sanctum')->name('user.register');
// 회원 탈퇴. 관리자만 호출 가능.
// Route::delete('/users', [UserController::class, 'register'])->middleware('auth:sanctum')->name('user.register');


// 회원 정보 조회
Route::get('/users/me', [UserController::class, 'retriveMe'])->middleware('auth:sanctum')->name('user.me');
Route::get('/users/{id}', [UserController::class, 'retriveBasic'])->middleware('auth:sanctum')->name('user.retrive');

// 회원 정보 수정
Route::put('/users/{id}', [UserController::class, 'update'])->middleware('auth:sanctum')->name('user.update');

// 회원 탈퇴
Route::delete('/users/{id}', [UserController::class, 'setStateDeleted'])->middleware('auth:sanctum')->name('user.delete');

// 기업 회원가입
Route::post('/users/{id}/company', [UserController::class, 'registerCompany'])->middleware('auth:sanctum')->name('user.registerCompany');


// 임시 비밀번호 발급
Route::put('/users/password/reset', [UserController::class, 'resetPassword'])->name('user.register');

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