<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

Route::prefix('auth')->group(function(){

    Route::middleware('auth:api')->group(function(){

        Route::patch('/change-password', [AuthController::class, 'changePassword']);

        Route::patch('/edit-account', [AuthController::class, 'editAccount']);
        
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

    Route::prefix('comments')->group(function(){
        
        Route::delete('/{comment}', [CommentController::class, 'deleteComment']);   
    });

    Route::prefix('users')->group(function(){
        
        Route::get('/', [UserController::class, 'users']);   

        Route::get('/{user}', [UserController::class, 'user']);   

        Route::get('/{user}/photos', [UserController::class, 'photos']);  

        Route::get('/{user}/posts', [UserController::class, 'posts']);   

        Route::get('/{user}/followers', [UserController::class, 'followers']);   

        Route::get('/{user}/followings', [UserController::class, 'followings']);   

        Route::post('/{user}/toggle-follow', [UserController::class, 'toggleFollow']);   
    });
});
