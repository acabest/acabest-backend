<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'student'], function () {
    Route::post('/register', 'Student\AuthController@register');
    Route::post('/login', 'Student\AuthController@login');
    Route::get('/email/resend', 'Student\VerificationController@resend')->name('verification.resend');
    Route::get('/email/verify/{id}/{hash}', 'Student\VerificationController@verify')->name('verification.verify');
    Route::post('/password/reset', 'Student\PasswordResetController@create');
    Route::get('/password/reset/{token}', 'Student\PasswordResetController@find');
    Route::post('/password/reset', 'Student\PasswordResetController@reset');
    Route::get('/details', 'Student\AuthController@studentDetails');
});

Route::get('/programs', 'ProgramController@index');
