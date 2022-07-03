<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use Illuminate\Http\Request;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::controller(AuthController::class)->name('v1.')->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::post('/register', 'register')->name('register');
});


Route::middleware('auth:sanctum')->name('v1.')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::controller(CourseController::class)->prefix('courses')->group(function () {
        Route::get('/', 'index')->name('get');
        Route::get('/{course}/details', 'getCourseDetails')->name('details');
        Route::get('/search', 'searchCourses')->name('search');
        Route::get('/search-categories', 'searchCategories')->name('search-category');
        Route::post('/{course}/apply-coupon', 'applyCoupon')->name('apply-coupon');
    });

});
