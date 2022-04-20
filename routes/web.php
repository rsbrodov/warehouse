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
Route::get('/gvt', [App\Http\Controllers\HomeController::class, 'gridViewTest'])->name('home-gvt');


//Route::get('/users/link/{data}', [UsersController::class, 'linkHandler'])->name('link.handler');

// ПОЛЬЗОВАТЕЛИ
//Route::resource('users', 'usersController'); // это надо вообще? в инструкции было так, но с этой строчкой странно работает:)
Route::get('/users/roles-create-view', [UsersController::class, 'rolesCreateView'])->name('users.roles-create-view');
Route::get('/users/roles/{id}/delete', [UsersController::class, 'deleteRole'])->name('users.delete-role');
Route::get('/users/permissions/{id}/delete', [UsersController::class, 'deletePermission'])->name('users.delete-permission');
Route::post('/users/roles-create-form/{type_action}', [UsersController::class, 'rolesCreateForm'])->name('users.roles-create-form');
Route::post('/users/roles-assign/', [UsersController::class, 'assignRole'])->name('users.roles-assign');
Route::middleware(['auth:web'])->group(function () {
    Route::get('/users/', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('/users/', [UsersController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UsersController::class, 'update'])->name('users.update'); // put работает, если в форму добавить @method('PUT')
    Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{id}/activate', [UsersController::class, 'activate'])->name('users.activate');
    Route::get('/users/{id}/block', [UsersController::class, 'block'])->name('users.block');
    Route::get('/users/{id}/delete', [UsersController::class, 'delete'])->name('users.delete');
});
// СПРАВОЧНИКИ
Route::middleware(['auth:web'])->group(function () {
    Route::get('/dictionary/', [DictionaryController::class, 'index'])->name('dictionary.index');
    Route::get('/dictionary/index2', [DictionaryController::class, 'index2'])->name('dictionary.index2');
    Route::post('/dictionary/', [DictionaryController::class, 'store'])->name('dictionary.store');
    Route::get('/dictionary/create', [DictionaryController::class, 'create'])->name('dictionary.create');
    Route::post('/dictionary/', [DictionaryController::class, 'store'])->name('dictionary.store');
    Route::get('/dictionary/{id}', [DictionaryController::class, 'show'])->name('dictionary.show');
    Route::get('/dictionary/{id}/edit', [DictionaryController::class, 'edit'])->name('dictionary.edit');
    Route::put('/dictionary/{id}', [DictionaryController::class, 'update'])->name('dictionary.update');
    Route::delete('/dictionary/{id}', [DictionaryController::class, 'destroy'])->name('dictionary.destroy');
    Route::get('/dictionary/{id}/archive', [DictionaryController::class, 'archive'])->name('dictionary.archive');
    Route::get('/dictionary/{id}/delete', [DictionaryController::class, 'delete'])->name('dictionary.delete');
});
// ЭЛЕМЕНТЫ СПРАВОЧНИКОВ
Route::middleware(['auth:web'])->group(function () {
    Route::get('dictionary/{id}/dictionary-element/', [DictionaryElementController::class, 'index'])->name('dictionary-element.index');
    Route::get('dictionary/{id}/dictionary-element/create', [DictionaryElementController::class, 'create'])->name('dictionary-element.create');
    Route::post('dictionary/{id}/dictionary-element/', [DictionaryElementController::class, 'store'])->name('dictionary-element.store');
    Route::get('dictionary-element/{id}', [DictionaryElementController::class, 'show'])->name('dictionary-element.show');
    Route::get('dictionary-element/{id}/edit', [DictionaryElementController::class, 'edit'])->name('dictionary-element.edit');
    Route::put('dictionary-element/{id}', [DictionaryElementController::class, 'update'])->name('dictionary-element.update');
    Route::delete('dictionary-element/{id}', [DictionaryElementController::class, 'destroy'])->name('dictionary-element.destroy');
});
// ТИПЫ КОНТЕНТА
Route::get('/type-content/create-icons', [TypeContentController::class, 'createIcons'])->name('type-content.create-icons');
Route::post('/type-content/get-icons', [TypeContentController::class, 'getIcons'])->name('type-content.get-icons');


Route::middleware(['auth:web'])->group(function () {
    Route::get('/type-content/', [TypeContentController::class, 'index'])->name('type-content.index');
    Route::get('/type-content/getListTypeContent', [TypeContentController::class, 'getListTypeContent'])->name('type-content.getListTypeContent');
    Route::get('/type-content/create', [TypeContentController::class, 'create'])->name('type-content.create');
    Route::post('/type-content/', [TypeContentController::class, 'store'])->name('type-content.store');
    Route::get('/type-content/{id}/edit', [TypeContentController::class, 'edit'])->name('type-content.edit');
    Route::get('/type-content/{id}/publish', [TypeContentController::class, 'publish'])->name('type-content.publish');
    Route::get('/type-content/enter/{id}', [TypeContentController::class, 'enter'])->name('type-content.enter');
    Route::get('/all-version-type-content/{id}', [TypeContentController::class, 'getAllVersionTypeContent'])->name('type-content.get-all-version'); // норм что здесь нет type_content?
    Route::get('/descript-version-type-content/{id}', [TypeContentController::class, 'getShowDescription'])->name('type-content.descript-version');
    Route::get('/type-content/{id}/{type}', [TypeContentController::class, 'createElemen'])->name('type-content.create-elemen');
    Route::get('/type-content/{id}', [TypeContentController::class, 'show'])->name('type-content.show');
    Route::get('/type-content/{id}/{parametr}', [TypeContentController::class, 'createNewVersion'])->name('type-content.create-new-version');
    Route::put('/type-content/{id}', [TypeContentController::class, 'update'])->name('type-content.update');
    Route::delete('/type-content/{id}', [TypeContentController::class, 'destroy'])->name('type-content.destroy');
});


