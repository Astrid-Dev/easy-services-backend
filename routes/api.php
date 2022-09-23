<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\EnquiryTypeController;
use App\Http\Controllers\HabitationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceProviderController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

///////////////////////////////////////////////////////////////////////////////

Route::resource('enquiries', EnquiryController::class);
Route::resource('enquiries_types', EnquiryTypeController::class);
Route::resource('habitations', HabitationController::class);
Route::resource('services', ServiceController::class);
Route::resource('services_providers', ServiceProviderController::class);

///////////////////////////////////////////////////////////////////////////////

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/logout', [UserAuthController::class, 'logout']);
    Route::post('/refresh', [UserAuthController::class, 'refresh']);
    Route::get('/user-profile', [UserAuthController::class, 'userProfile']);
});

///////////////////////////////////////////////////////////////////////////////
