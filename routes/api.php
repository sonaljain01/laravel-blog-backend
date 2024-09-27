<?php
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CatrgoryController;
use App\Http\Controllers\ChildCatrgoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UpdateProfileController;

Route::get("/", function () {
    return response()->json([
        "status" => "up",
        "message" => "Welcome to Blog API",
        "time" => now()
    ]);
});


Route::group(["prefix" => "auth"], function () {
    Route::post("register", [AuthController::class, "register"]);
    Route::post("login", [AuthController::class, "login"]);
});

Route::group(["prefix" => "user"], function () {
    Route::group(["middleware" => "auth:api"], function () {
        Route::get("profile", [AuthController::class, "profile"]);
        Route::put("profile/update/{id}", [UpdateProfileController::class, "updateProfile"]);
        Route::get("logout", [AuthController::class, "logout"]);
    });
});


Route::group(["prefix" => "blog"], function () {
    Route::group(["middleware" => "auth:api"], function () {
        Route::get("/user", [BlogController::class, "displayuserBlog"]);
        Route::post("create", [BlogController::class, "store"]);
        Route::put("update/{slug}", [BlogController::class, "update"]);
        Route::delete("delete/{id}", [BlogController::class, "destroy"]);
    });
    Route::get("/", [BlogController::class, "display"]);
    Route::get("/{slug}", [BlogController::class, "displaySpecificBlog"]);
});

Route::group(["prefix" => "admin/blog"], function () {
    Route::group(["middleware" => "auth:api"], function () {
        Route::get("/", [AdminController::class, "display"]);
        Route::post("create", [AdminController::class, "store"]);
        Route::put("update", [AdminController::class, "update"]);
        Route::delete("delete/{id}", [AdminController::class, "destroy"]);
    });

});

Route::group(["prefix" => "category"], function () {
    Route::group(["middleware" => "auth:api"], function () { });
    Route::get("/", [CatrgoryController::class, "display"]);
    Route::post("create", [CatrgoryController::class, "store"]);
    Route::put("update/{id}", [CatrgoryController::class, "update"]);
    Route::delete("delete/{id}", [CatrgoryController::class, "destroy"]);
});

Route::group(["prefix" => "category/child"], function () {
    Route::group(["middleware" => "auth:api"], function () { });
    Route::get("/{id}", [ChildCatrgoryController::class, "display"]);
    Route::post("create", [ChildCatrgoryController::class, "store"]);
    Route::put("update/{id}", [ChildCatrgoryController::class, "update"]);
    Route::delete("delete/{id}", [ChildCatrgoryController::class, "destroy"]);
});

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
        Route::post("create", [CommentController::class, "store"]);
        Route::post("update/{id}", [CommentController::class, "update"]);
        Route::post("delete/{id}", [CommentController::class, "destroy"]);
    });
    Route::get("/{id}", [CommentController::class, "display"]);
});

Route::group(["prefix" => "rating"], function () {
    Route::group(["middleware" => "auth:api"], function () {
        Route::post("create", [RatingController::class, "store"]);
    });
    Route::get("/{id}", [RatingController::class, "display"]);
    Route::get("avg/{id}", [RatingController::class, "displayavgRating"]);
});
