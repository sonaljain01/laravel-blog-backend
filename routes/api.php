<?php
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CatrgoryController;
use App\Http\Controllers\ChildCatrgoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;

Route::get("/", function () {
    return response()->json([
        "status" => "up",
        "message" => "Welcome to Blog API",
        "time" => now()
    ]);
});


Route::group(["prefix" => "auth"], function () {
    Route::post("register", [ApiController::class, "register"]);
    Route::post("login", [ApiController::class, "login"]);
});

Route::group(["prefix" => "user"], function () {
    Route::get("profile", [ApiController::class, "profile"]);
    Route::get("logout", [ApiController::class, "logout"]);
})->middleware("auth:api");


Route::group(["prefix" => "blog"], function () {
    Route::get("/", [BlogController::class, "display"]);
    Route::get("/{id}", [BlogController::class, "displaySpecificBlog"]);
    Route::post("create", [BlogController::class, "store"]);
    Route::put("update", [BlogController::class, "update"]);
    Route::delete("delete/{id}", [BlogController::class, "destroy"]);
})->middleware("auth:api");

Route::group(["prefix" => "admin/blog"], function () {
    Route::get("/", [AdminController::class, "display"]);
    Route::post("create", [AdminController::class, "store"]);
    Route::put("update", [AdminController::class, "update"]);
    Route::delete("delete/{id}", [AdminController::class, "destroy"]);
})->middleware("auth:api");

Route::group(["prefix" => "category"], function () {
    Route::get("/", [CatrgoryController::class, "display"]);
    Route::post("create", [CatrgoryController::class, "store"]);
    Route::put("update/{id}", [CatrgoryController::class, "update"]);
    Route::delete("delete/{id}", [CatrgoryController::class, "destroy"]);
})->middleware("auth:api");

Route::group(["prefix" => "category/child"], function () {
    Route::get("/", [ChildCatrgoryController::class, "display"]);
    Route::post("create", [ChildCatrgoryController::class, "store"]);
    Route::put("update/{id}", [ChildCatrgoryController::class, "update"]);
    Route::delete("delete/{id}", [ChildCatrgoryController::class, "destroy"]);
})->middleware("auth:api");

Route::group(["prefix" => "tag"], function () {
    Route::group(["middleware" => "auth:api"], function () {
        Route::get("/", [TagController::class, "display"]);
        Route::post("create", [TagController::class, "store"]);
        Route::post("update/{id}", [TagController::class, "update"]);
        Route::post("delete/{id}", [TagController::class, "destroy"]);
    });
});

Route::group(["prefix" => "comment"], function () {
    Route::group(["middleware" => "auth:api"], function () {
        Route::get("/{id}", [CommentController::class, "display"]);
        Route::post("create", [CommentController::class, "store"]);
        Route::post("update/{id}", [CommentController::class, "update"]);
        Route::post("delete/{id}", [CommentController::class, "destroy"]);
    });
});

Route::group(["prefix" => "rating"], function () {
    Route::group(["middleware" => "auth:api"], function () {
        Route::get("/{id}", [RatingController::class, "display"]);
        Route::get("avg/{id}", [RatingController::class, "displayavgRating"]);
        Route::post("create", [RatingController::class, "store"]);
    });
});


