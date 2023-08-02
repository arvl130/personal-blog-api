<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
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

// Public routes
Route::get("/posts", [PostController::class, "index"])->name("posts.index");
Route::get("/posts/{id}", [PostController::class, "show"])->name("posts.show");
Route::post("/register", [AuthController::class, "register"])->name("auth.register");
Route::post("/login", [AuthController::class, "login"])->name("auth.login");

// Protected routes
Route::middleware("auth:sanctum")->group(function () {
    Route::post("/posts", [PostController::class, "store"])->name("posts.create");
    Route::patch("/posts/{id}", [PostController::class, "update"])->name("posts.update");
    Route::delete("/posts/{id}", [PostController::class, "destroy"])->name("posts.destroy");

    Route::delete("/logout", [AuthController::class, "logout"])->name("auth.logout");
});
