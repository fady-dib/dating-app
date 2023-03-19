<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserAction;

// Route::controller(AuthController::class)->group(function () {
//     Route::post('/login', 'login');
//     Route::post('/register', 'register');
//     Route::post('/logout', 'logout');
//     Route::post('/refresh', 'refresh');
//     Route::get('/log','test');
//     // Route::patch('editprofile/{id}','editProfile');
//     // Route::get('user/{id}','getUsers');

// });

Route::group([
], function(){
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/refresh', [AuthController::class, 'refresh']); 
    // Route::post('/log', [AuthController::class, 'test']);
    Route::post('/editprofile',[UserAction::class,"editProfile"]);
    
});


Route::get('/log',[UserAction::class,"test"]);
Route::get('/users',[UserAction::class,"getUsers"]);
Route::post('/block',[UserAction::class,"block"]);
Route::post('/unblock',[UserAction::class,"unblock"]);
Route::post('/favorite',[UserAction::class,"favorite"]);
Route::post('/unfavorite',[UserAction::class,"unfavorite"]);