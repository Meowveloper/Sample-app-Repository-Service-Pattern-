<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProblemOptionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
// Redirect root to /dashboard
Route::middleware('guest:admin')->group(function(){
    Route::get('admin/login',[LoginController::class,'loginPage'])->name('admin.login');
    Route::post('admin/login', [LoginController::class,'login']);
    Route::post('/logout',[LoginController::class,'logout']);
});

Route::middleware('auth.admin')->group(function () {
    // Your admin user routes go here
    Route::redirect('/', '/dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/categories', CategoryController::class);
    Route::resource('/problems', ProblemController::class);
    Route::resource('/problem-options', ProblemOptionController::class);
    Route::resource('/users', UserController::class);
    Route::resource('/admins', AdminController::class);
});


