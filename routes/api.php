<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\EnquiryModificationHistoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationEmployeeController;
use App\Http\Controllers\ServiceProviderController;
use App\Http\Controllers\ServiceProviderApplicationController;

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
Route::resource('answers', AnswerController::class);
Route::resource('questions', QuestionController::class);
Route::resource('services', ServiceController::class);
Route::resource('organizations', OrganizationController::class);
Route::resource('service_providers', ServiceProviderController::class);
Route::resource('service_provider_applications', ServiceProviderApplicationController::class);

///////////////////////////////////////////////////////////////////////////////

Route::group([
    'middleware' => 'simple_user',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/logout', [UserAuthController::class, 'logout']);
    Route::post('/refresh', [UserAuthController::class, 'refresh']);
    Route::get('/user_profile', [UserAuthController::class, 'userProfile']);
    Route::put('/update_profile/{id}', [UserAuthController::class, 'updateProfile']);
    Route::put('/update_password/{id}', [UserAuthController::class, 'updatePassword']);
});

Route::group([
    'middleware' => 'simple_user',
    'prefix' => 'notifications'
], function ($router) {
    Route::get('/', [NotificationController::class, 'index']);
    Route::delete('/{id}', [NotificationController::class, 'destroy']);
    Route::post('/{id}', [NotificationController::class, 'read']);
});

Route::group([
    // 'middleware' => 'organization',
    'prefix' => 'organization_employees'
], function ($router) {
    Route::get('/search_for_user', [OrganizationEmployeeController::class, 'search_for_user']);
    Route::get('/search_some_providers_for_request/{organization_id}', [OrganizationEmployeeController::class, 'search_some_providers_for_request']);
    Route::post('/register_new_employee', [OrganizationEmployeeController::class, 'register_new_employee']);
});

///////////////////////////////////////////////////////////////////////////////

Route::get('statistics', [EnquiryModificationHistoryController::class, 'statistics']);
