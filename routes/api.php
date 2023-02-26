<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

Route::prefix('auth')->group(function(){

    Route::middleware('auth:api')->group(function(){

        Route::patch('/change-password', [AuthController::class, 'changePassword']);

        Route::patch('/edit-profile', [AuthController::class, 'editProfile']);
        
        Route::delete('/logout', [AuthController::class, 'logout']);
    });

    Route::get('/', [AuthController::class, 'account']);
    
    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:api')->group(function(){

    Route::prefix('posts')->group(function(){
    
        Route::post('/', [PostController::class, 'createPost']);
    
        Route::delete('/{post}', [PostController::class, 'deletePost']);      

        Route::post('/{post}/toggle-like', [PostController::class, 'toggleLike']);

        Route::post('/{post}/comments', [PostController::class, 'createComment']); 

        Route::get('/{post}/comments', [PostController::class, 'comments']);  

        Route::get('/feeds', [PostController::class, 'feeds']);    
    });

    Route::prefix('users')->group(function(){
        
        Route::get('/', [UserController::class, 'users']);   

        Route::get('/{user}/photos', [UserController::class, 'photos']);  

        Route::get('/{user}/posts', [UserController::class, 'posts']);   

        Route::get('/{userId}/followers', [UserController::class, 'followers']);   

        Route::get('/me/followings', [UserController::class, 'myFollowings']);

        Route::get('/{user}/followings', [UserController::class, 'followings']);   

        Route::patch('/{user}/toggle-follow', [UserController::class, 'toggleFollow']);   

        Route::get('/{user}', [UserController::class, 'user']);  
        
        Route::delete('/me/comments/{comment}', [UserController::class, 'deleteComment']);   
    });
});
