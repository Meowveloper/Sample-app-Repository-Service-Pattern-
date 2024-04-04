<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProblemOptionController;

Route::middleware('auth.admin')->group(function () {
    // Your admin user routes go here
    Route::post('/upload-problem-image', [ProblemController::class, 'uploadProblemImage']);
    Route::get('/get-all-problems', [ProblemController::class, 'getAllProblems']);

    Route::post('/save-problem-data',[ProblemController::class,'store']);
    Route::post('/update-problem-data/{id}',[ProblemController::class,'update']);
    Route::post('/delete-problem',[ProblemController::class,'destroy']);

    //Get All Categories Table Data
    Route::get('/get-all-categories', [CategoryController::class, 'getAllCategories']);
    //Search Categories
    Route::get('/categories', [CategoryController::class, 'getCategoryData']);
    // Category Save / Update / Delete
    Route::post('/save-category-data', [CategoryController::class, 'store']);
    Route::post('/update-category-data/{id}', [CategoryController::class, 'update']);
    Route::post('/delete-category', [CategoryController::class, 'destroy']);

    // Get All Problem Options Table Data
    Route::get('/get-all-problem-options',[ProblemOptionController::class,'getAllProblemOptionsTableData']);
    // Problem Option  Update
    Route::post('/update-problem-option/{id}',[ProblemOptionController::class,'update']);
    //Search Problem Options
    Route::get('/problem-options',[ProblemOptionController::class,'getProblemOptionData']);

    //from admin problem
    Route::get('/get-problem-options/{id}',[ProblemOptionController::class,'getAllProblemOptions']);

    //Get all users table data
    Route::get('/get-all-users',[UserController::class,'getAllUsers']);
    // search users
    Route::get('/users', [UserController::class, 'getUserData']);

    //admin table data
    Route::get('/get-all-admins',[AdminController::class,'getAllAdminsTableData']);
    //admin  data by search
    Route::get('/admins',[AdminController::class,'getAdminData']);
    Route::get('/auth-check',[LoginController::class,'authCheck']);
    Route::post('/save-admin-data',[AdminController::class,'store']);
    Route::post('/update-admin-data/{id}',[AdminController::class,'update']);
    Route::post('/delete-admin', [AdminController::class, 'destroy']);
    // logout
    Route::post('/logout',[LoginController::class,'logout']);
});

