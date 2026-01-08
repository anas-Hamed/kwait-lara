<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\ContactUsController;
use App\Http\Controllers\API\NotificationApiController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\SettingController;
use App\Http\Middleware\CheckIfActive;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/** Login Stuff API */
Route::post('user/login', [AuthController::class, 'login'])->name('login');
Route::post('user/register', [AuthController::class, 'register']);
/** Password Stuff API */
Route::post('/password/send-reset-email', [AuthController::class, 'sendPasswordResetLinkEmail']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);


/** Global API */
Route::get('main', [CategoryController::class, 'main']);
Route::resource('category', CategoryController::class);
Route::get('search', SearchController::class);
Route::get('setting', SettingController::class);
Route::get('blog', [BlogController::class, 'index']);
Route::get('blog/{slug}', [BlogController::class, 'show']);
Route::post('contact-us', ContactUsController::class);


Route::middleware('auth:sanctum')->group(function () {
    Route::middleware(CheckIfActive::class)->group(function () {
        Route::put('user', [AuthController::class, 'updateUser']);
        Route::post('user/save-fcm', [AuthController::class, 'saveFcm']);
        Route::put('user/update-password', [AuthController::class, 'updatePassword']);
        Route::post('company/rate', [CompanyController::class, 'rate']);
        Route::post('company/{company}/toggle-favorite', [CompanyController::class, 'toggleFavorite']);
        Route::post('company/update/{company}', [CompanyController::class, 'update']);
        Route::post('company', [CompanyController::class, 'store']);
        Route::delete('company/{company}', [CompanyController::class, 'destroy']);
        Route::post('trust-company/{company}', [CompanyController::class, 'trust']);

    });

    Route::get('user', [AuthController::class, 'getUser']);
    Route::get('favorite', [CompanyController::class, 'userFavorite']);
    Route::get('company/mine', [CompanyController::class, 'getMyCompany']);

    /** Notification API */
    Route::post("notifications/makeAsRead/{notification}", [NotificationApiController::class, "makeAsRead"]);
    Route::post("notifications/makeAllAsRead", [NotificationApiController::class, "makeAllAsRead"]);
    Route::get("notifications/numberUnread", [NotificationApiController::class, "numberUnread"]);
    Route::get("notifications", [NotificationApiController::class, 'index']);

});
Route::get('company', [CompanyController::class, 'index']);
Route::get('company/{slug}/show', [CompanyController::class, 'slugShow']);
Route::get('company/{company}', [CompanyController::class, 'show']);
