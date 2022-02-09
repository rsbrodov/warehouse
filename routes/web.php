<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TechController;
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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/users/user-create-view', [UsersController::class, 'userCreateView'])->name('users.user-create-view');
Route::post('/users/user-create-form', [UsersController::class, 'userCreateForm'])->name('users.user-create-form');
Route::get('/users/user-edit-view/edit{id}', [UsersController::class, 'userEditView'])->name('users.user-edit-view');
Route::post('/users/user-edit-view/edit{id}', [UsersController::class, 'userEditForm'])->name('users.user-edit-form');
Route::get('/users/user-delete-button/del{id}', [UsersController::class, 'userDeleteButton'])->name('users.user-delete-button');
Route::get('/users/user-block-button/bl{id}', [UsersController::class, 'userBlockButton'])->name('users.user-block-button');

//Route::get('/login', [TechController::class, 'create'])->name('login');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/tech/create', [TechController::class, 'create'])->name('tech.create');
Route::get('/tech/index', [TechController::class, 'index'])->name('tech.index');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home2', [App\Http\Controllers\HomeController::class, 'index2'])->name('home2');


//Route::get('/users/link/{data}', [UsersController::class, 'linkHandler'])->name('link.handler');


