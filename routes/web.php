<?php

use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\DictionaryElementController;
use App\Http\Controllers\TypeContentController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TechController;

use App\Http\Controllers\YurkController;
use App\Http\Controllers\TestController;


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



Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/tech/create', [TechController::class, 'create'])->name('tech.create');
Route::get('/tech/index', [TechController::class, 'index'])->name('tech.index');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home2', [App\Http\Controllers\HomeController::class, 'index2'])->name('home2');
Route::get('/home3', [App\Http\Controllers\HomeController::class, 'index3'])->name('home3');


//Route::get('/users/link/{data}', [UsersController::class, 'linkHandler'])->name('link.handler');

// ПОЛЬЗОВАТЕЛИ
//Route::resource('users', 'usersController'); // это надо вообще? в инструкции было так, но с этой строчкой странно работает:)
Route::get('/users/roles-create-view', [UsersController::class, 'rolesCreateView'])->name('users.roles-create-view');
Route::get('/users/roles/{id}/delete', [UsersController::class, 'deleteRole'])->name('users.delete-role');
Route::get('/users/permissions/{id}/delete', [UsersController::class, 'deletePermission'])->name('users.delete-permission');
Route::post('/users/roles-create-form/{type_action}', [UsersController::class, 'rolesCreateForm'])->name('users.roles-create-form');
Route::post('/users/roles-assign/', [UsersController::class, 'assignRole'])->name('users.roles-assign');

Route::get('/users/', [UsersController::class, 'index'])->name('users.index');
Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
Route::post('/users/', [UsersController::class, 'store'])->name('users.store');
Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');
Route::get('/users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
Route::post('/users/{id}', [UsersController::class, 'update'])->name('users.update'); // put почему-то не работает
Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
Route::get('/users/{id}/activate', [UsersController::class, 'activate'])->name('users.activate');
Route::get('/users/{id}/block', [UsersController::class, 'block'])->name('users.block');
Route::get('/users/{id}/delete', [UsersController::class, 'delete'])->name('users.delete');
// СПРАВОЧНИКИ
Route::get('/dictionary/', [DictionaryController::class, 'index'])->name('dictionary.index');
Route::post('/dictionary/', [DictionaryController::class, 'store'])->name('dictionary.index');
Route::get('/dictionary/create', [DictionaryController::class, 'create'])->name('dictionary.create');
Route::post('/dictionary/', [DictionaryController::class, 'store'])->name('dictionary.store');
Route::get('/dictionary/{id}', [DictionaryController::class, 'show'])->name('dictionary.show');
Route::get('/dictionary/{id}/edit', [DictionaryController::class, 'edit'])->name('dictionary.edit');
Route::post('/dictionary/{id}', [DictionaryController::class, 'update'])->name('dictionary.update');
Route::delete('/dictionary/{id}', [DictionaryController::class, 'destroy'])->name('dictionary.destroy');
Route::get('/dictionary/{id}/archive', [DictionaryController::class, 'archive'])->name('dictionary.archive');
Route::get('/dictionary/{id}/delete', [DictionaryController::class, 'delete'])->name('dictionary.delete');
// ЭЛЕМЕНТЫ СПРАВОЧНИКОВ
Route::get('dictionary/{dic_id}/dictionary_element/', [DictionaryElementController::class, 'index'])->name('dictionary_element.index');
Route::get('dictionary/{dic_id}/dictionary_element/create', [DictionaryElementController::class, 'create'])->name('dictionary_element.create');
Route::post('dictionary/{dic_id}/dictionary_element/', [DictionaryElementController::class, 'store'])->name('dictionary_element.store');
Route::get('dictionary_element/{id}', [DictionaryElementController::class, 'show'])->name('dictionary_element.show');
Route::get('dictionary_element/{id}/edit', [DictionaryElementController::class, 'edit'])->name('dictionary_element.edit');
Route::post('dictionary_element/{id}', [DictionaryElementController::class, 'update'])->name('dictionary_element.update');
Route::delete('dictionary_element/{id}', [DictionaryElementController::class, 'destroy'])->name('dictionary_element.destroy');
// ТИПЫ КОНТЕНТА
Route::get('/type_content/', [TypeContentController::class, 'index'])->name('type_content.index');
Route::get('/type_content/shablon-version-index', [TypeContentController::class, 'shablonVersionIndex'])->name('type_content.shablon-version-index');
Route::get('/type_content/create', [TypeContentController::class, 'create'])->name('type_content.create');
Route::post('/type_content/', [TypeContentController::class, 'store'])->name('type_content.store');
Route::get('/type_content/{id}', [TypeContentController::class, 'show'])->name('type_content.show');
Route::get('/type_content/{id}/edit', [TypeContentController::class, 'edit'])->name('type_content.edit');
Route::post('/type_content/{id}', [TypeContentController::class, 'update'])->name('type_content.update');
Route::delete('/type_content/{id}', [TypeContentController::class, 'destroy'])->name('type_content.destroy');


