<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\PostController;
use \App\Http\Controllers\CommentController;


Route::post('/signup', [UserController::class, 'registerUser']);
Route::post('/login', [UserController::class, 'loginUser']);
Route::post("/createcomment", [CommentController::class, "createComment"]);
Route::get("/getposts", [PostController::class, "getAllPosts"]);
Route::get("/getpostbyid/{id}", [PostController::class, "getPostById"]);
Route::get("/getcomments/{id}", [CommentController::class, "getCommentsByPostId"]);

Route::middleware(['auth:sanctum', 'isverified'])->group(function () {
    Route::post("/createpost", [PostController::class, "makePost"]);
    Route::delete("/deletepost/{id}", [PostController::class, "deletePost"]);
    Route::patch("/updatepost/{id}", [PostController::class, "updatePost"]);
    Route::delete("/deletecomment/{id}", [CommentController::class, "deleteComment"]);
});

Route::middleware(['auth:sanctum', "isAdmin"])->group(function () {
    Route::patch("/verifyuser/{id}", [UserController::class, "verifyUser"]);
});
