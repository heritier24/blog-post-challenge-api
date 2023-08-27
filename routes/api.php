<?php

use App\Http\Controllers\BlogsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/author", [BlogsController::class, "creatingAuthor"]);

Route::post("/post-blog", [BlogsController::class, "publishBlogPosts"]);

Route::get("/list-blogs-posted", [BlogsController::class, "listBlogPosted"]);

Route::get("/list-blog/{postID}", [BlogsController::class, "listBlogPostedDetails"])->whereNumber("postID");

Route::put("/update-post-blog/{postID}", [BlogsController::class, "updateBlogPost"])->whereNumber("postID");

Route::delete("/delete-post-blog/{authorID}/{postID}", [BlogsController::class, "deleteBlogPost"]);
