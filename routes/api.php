<?php

use App\Http\Controllers\API\V1\Auth\UserEmailVerificationController;
use App\Http\Controllers\API\V1\Auth\UserLoginController;
use App\Http\Controllers\API\V1\Auth\UserLogoutController;
use App\Http\Controllers\API\V1\Auth\UserPasswordController;
use App\Http\Controllers\API\V1\Auth\UserProfileController;
use App\Http\Controllers\API\V1\Auth\UserRegisterController;
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

Route::POST('auth/login',UserLoginController::class);

Route::POST('auth/register',UserRegisterController::class);

Route::group(['middleware' => 'auth:api'], function () {

    // User logout route
    Route::post('auth/logout', UserLogoutController::class);

    // User password routes
    Route::controller(UserPasswordController::class)->group(function () {
        Route::post('auth/profile/password/change', 'changePassword');
    });

    // User profile routes
    Route::controller(UserProfileController::class)->group(function () {
        // Display user profile
        Route::get('auth/profile', 'show');

        // Update user profile
        Route::post('auth/profile', 'update');
    });
});


// User email verification routes
Route::middleware(['api'])->group(function () {
    Route::get('auth/email/verify/{id}', [UserEmailVerificationController::class,'verify'])
        ->name('verification.verify');

    Route::get('auth/email/resend', [UserEmailVerificationController::class,'resend'])
        ->name('verification.resend')->middleware('auth:api');
});
