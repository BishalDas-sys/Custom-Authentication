<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\crudcontroller;
use App\Http\Controllers\PostCRUDController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// 
Route::get('/', function () {
    return view('login');
});

// Route::get('/login', [CustomAuthController::class,'login'])->middleware('alreadyLoggedIn');
Route::get('/', [CustomAuthController::class,'login'])->middleware('alreadyLoggedIn');
Route::get('/login', [CustomAuthController::class,'login'])->middleware('alreadyLoggedIn');
Route::get('/registration', [CustomAuthController::class,'registration'])->middleware('alreadyLoggedIn');
Route::post('/register-user',[CustomAuthController::class,'registerUser'])->name('register-user');
Route::post('login-user',[CustomAuthController::class,'loginUser'])->name('login-user');
Route::get('/logout',[CustomAuthController::class,'logout']);


// CRUD ROUTES
Route::get('employees', [EmployeeController::class, 'index'])->middleware('isLoggedIn');
Route::get('add-employee', [EmployeeController::class, 'create'])->middleware('isLoggedIn');
Route::post('add-employee', [EmployeeController::class, 'store']);
Route::get('edit-employee/{id}', [EmployeeController::class, 'edit'])->middleware('isLoggedIn');
Route::put('update-employee/{id}', [EmployeeController::class, 'update']);
Route::delete('delete-employee/{id}', [EmployeeController::class, 'destroy']);
Route::alldelete('delete-employees', [EmployeeController::class, 'destroyall']);

