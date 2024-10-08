<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController as AuthController;
use App\Http\Controllers\UserController as UserController;
use App\Http\Controllers\EventController as EventController;

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

// 회원 상세 정보 조회. 관리자만 호출 가능
Route::get('/users/{id}/detail', [UserController::class, 'retriveDetail'])->middleware('auth:sanctum')->name('user.retriveDetail');


// 회원 정보 수정
Route::put('/users/{id}', [UserController::class, 'update'])->middleware('auth:sanctum')->name('user.update');

// 회원 탈퇴
Route::delete('/users/{id}', [UserController::class, 'setStateDeleted'])->middleware('auth:sanctum')->name('user.delete');

// 기업 회원가입 및 기업 정보 수정. 수정할 경우 accept가 0(대기) 상태로 돌아감
Route::post('/users/{id}/company', [UserController::class, 'registerCompany'])->middleware('auth:sanctum')->name('user.registerCompany');

// 기업 승인
Route::put('/users/{id}/company/accept', [UserController::class, 'setCompanyAccept'])->middleware('auth:sanctum')->name('user.setCompanyAccept');


// 임시 비밀번호 발급
Route::put('/users/password/reset', [UserController::class, 'resetPassword'])->name('user.register');

// 회원가입 후 이메일 인증
Route::get('/auth/verity/{token}', [AuthController::class, 'verityEmail'])->name('auth.verity');


// 이벤트 생성
Route::post('/events', [EventController::class, 'create'])->middleware('auth:sanctum')->name('event.create');

// 이벤트 기본 정보 조회 및 수정
Route::get('/events/{id}/basic', [EventController::class, 'retriveBasic'])->middleware('auth:sanctum')->name('event.retriveBasic');
Route::post('/events/{id}/basic', [EventController::class, 'updateBasic'])->middleware('auth:sanctum')->name('event.updateBasic');

// 이벤트 상세 페이지 조회 및 수정
Route::get('/events/{id}/detail', [EventController::class, 'create'])->middleware('auth:sanctum')->name('event.create');
Route::post('/events/{id}/detail', [EventController::class, 'create'])->middleware('auth:sanctum')->name('event.create');

// 이벤트 모집 정보 조회 및 수정
Route::get('/events/{id}/recurit', [EventController::class, 'create'])->middleware('auth:sanctum')->name('event.create');
Route::post('/events/{id}/recurit', [EventController::class, 'create'])->middleware('auth:sanctum')->name('event.create');

// 이벤트 모집 정보 조회 및 수정
Route::get('/events/{id}/survey', [EventController::class, 'create'])->middleware('auth:sanctum')->name('event.create');
Route::post('/events/{id}/survey', [EventController::class, 'create'])->middleware('auth:sanctum')->name('event.create');

// 이벤트 모집 정보 조회 및 수정
Route::get('/events/{id}/faq', [EventController::class, 'create'])->middleware('auth:sanctum')->name('event.create');
Route::post('/events/{id}/faq', [EventController::class, 'create'])->middleware('auth:sanctum')->name('event.create');



// 테스트를 위한 관리자 전환
Route::get('/user-to-admin', function (Request $request) {
    $user = $request->user();
    $user->is_admin = true;
    $user->save();
    return "OK";
})->middleware('auth:sanctum');




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