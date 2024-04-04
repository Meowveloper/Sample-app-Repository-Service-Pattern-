<?php

use App\Http\Controllers\Api\AnswerApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ProblemApiController;
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

// Example using a closure in routes


Route::get('/get-categories', [CategoryApiController::class, 'getAllCategory']);
Route::post('/get-problem-by-random', [ProblemApiController::class, 'getAllProblem']);
Route::post('/save-problem-data-with-answers', [ProblemApiController::class, 'saveProblemWithAnswers']);

Route::get('/login/facebook', [AuthApiController::class, 'redirectToFacebook']);
Route::get('/login/facebook/callback', [AuthApiController::class, 'handleFacebookCallback']);

Route::get('/login/google', [AuthApiController::class, 'redirectToGoogle']);
Route::get('/login/google/callback', [AuthApiController::class, 'handleGoogleCallback']);

Route::get('/login/apple', [AuthApiController::class, 'redirectToApple']);
Route::get('/login/apple/callback', [AuthApiController::class, 'handleAppleCallback']);

Route::post('/sign-in-with-facebook', [AuthApiController::class, 'signInWithFacebook']);
Route::post('/sign-in-with-google', [AuthApiController::class, 'signInWithGoogle']);
Route::post('/sign-in-with-apple', [AuthApiController::class, 'signInWithApple']);

Route::post('/save-user-answer', [AnswerApiController::class, 'saveUserAnswer']);
