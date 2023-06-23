<?php

use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\DictionaryElementController;
use App\Http\Controllers\TypeContentController;
use App\Http\Controllers\ElementContentController;
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
Route::get('/users-list', [App\Http\Controllers\UsersController::class, 'usersList']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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
    Route::get('/users/profile/', [UsersController::class, 'profile'])->name('users.profile');
    Route::put('/users/profile/{id}', [UsersController::class, 'profileUpdate'])->name('users.profile-update');
    Route::put('/users/profile-image-upload/{id}', [UsersController::class, 'profileImageUpload'])->name('users.profile-image-upload');
    Route::get('/users/profile-image-delete/{id}', [UsersController::class, 'profileImageDelete'])->name('users.profile-image-delete');
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
    Route::get('/dictionary/findDictionary', [DictionaryController::class, 'findDictionary']);
    Route::get('/dictionary/findDictionaryNotEmptyElement', [DictionaryController::class, 'findDictionaryNotEmptyElement']);
    Route::post('/dictionary/store/', [DictionaryController::class, 'store']);
    Route::get('/dictionary/', [DictionaryController::class, 'index'])->name('dictionary.index');
    Route::post('/dictionary/{id}', [DictionaryController::class, 'update'])->name('dictionary.update');
    Route::delete('/dictionary/{id}', [DictionaryController::class, 'destroy'])->name('dictionary.destroy');
    Route::get('/dictionary/{id}/archive', [DictionaryController::class, 'archive'])->name('dictionary.archive');
});
// ЭЛЕМЕНТЫ СПРАВОЧНИКОВ
Route::middleware(['auth:web'])->group(function () {
    Route::get('dictionary/{id}/dictionary-element/', [DictionaryElementController::class, 'index'])->name('dictionary-element.index');
    Route::get('dictionary/findElementDictionaryID/{id}', [DictionaryElementController::class, 'findElementDictionaryID']);
    Route::get('dictionary/findID/{id}', [DictionaryElementController::class, 'findID']);

    Route::get('dictionary-element/{id}', [DictionaryElementController::class, 'show'])->name('dictionary-element.show');
    Route::get('dictionary-element/{id}/edit', [DictionaryElementController::class, 'edit'])->name('dictionary-element.edit');
    Route::put('dictionary-element/{id}', [DictionaryElementController::class, 'update'])->name('dictionary-element.update');
    Route::delete('dictionary-element/{id}', [DictionaryElementController::class, 'destroy'])->name('dictionary-element.destroy');
});
// ТИПЫ КОНТЕНТА
Route::get('/type-content/icons', [TypeContentController::class, 'getIcons']);
Route::get('/type-content/create-icons', [TypeContentController::class, 'createIcons'])->name('type-content.create-icons');


Route::middleware(['auth:web'])->group(function () {
    Route::get('/api/template/GetTypeContentId/{id}', [TypeContentController::class, 'getApiUrl']);
    Route::get('/element/enter-vue/{id}', [TypeContentController::class, 'enterVue'])->name('type-content.enter-vue');
    Route::post('/type-content/save-body/', [TypeContentController::class, 'saveBody']);
    Route::post('/type-content/save-body-element/', [TypeContentController::class, 'saveBodyElement']);
    Route::get('/type-content/getTypeContentID/{id}', [TypeContentController::class, 'getTypeContentID']);
    Route::get('/type-content/get-body/{id}', [TypeContentController::class, 'getBody']);
    Route::get('/type-content/get-body-element-content/{id}', [TypeContentController::class, 'getBodyElementContent']);
    Route::post('/type-content/store/', [App\Http\Controllers\TypeContentController::class, 'store']);
    Route::post('/type-content/{id}', [TypeContentController::class, 'update']);
    Route::get('/type-content/view-new/{idGlobal}', [TypeContentController::class, 'viewNew'])->name('type-content.view-new');
    Route::get('/type-content/index', [TypeContentController::class, 'index'])->name('type-content.index');
    Route::get('/type-content/getListTypeContent', [TypeContentController::class, 'getListTypeContent'])->name('type-content.getListTypeContent');
    Route::get('/type-content/create', [TypeContentController::class, 'create'])->name('type-content.create');
    Route::post('/type-content/', [TypeContentController::class, 'store'])->name('type-content.store');
    Route::get('/type-content/{id}/edit', [TypeContentController::class, 'edit'])->name('type-content.edit');
    Route::get('/type-content/getAllVersionTypeContent/{id}', [TypeContentController::class, 'getAllVersionTypeContentWeb']);
    Route::get('/type-content/all-version-type-content/{id}', [TypeContentController::class, 'getAllVersion'])->name('type-content.all-version');
    Route::get('/descript-version-type-content/{id}', [TypeContentController::class, 'getShowDescription'])->name('type-content.descript-version');
    //Route::get('/type-content/{id}/{type}', [TypeContentController::class, 'createElemen'])->name('type-content.create-elemen');
    Route::get('/type-content/new-version/{id}/{parametr}', [TypeContentController::class, 'createNewVersion']);
    //Route::put('/type-content/{id}', [TypeContentController::class, 'update'])->name('type-content.update');
    Route::delete('/type-content/{id}', [TypeContentController::class, 'destroy'])->name('type-content.destroy');
    //Route::get('/type-content/dropdownlistby/{id}', [TypeContentController::class, 'getDropdownListById'])->name('type-content.getDropdownListById');
    Route::get('/select/dropdownlistby/{id}', [TypeContentController::class, 'getDropdownListById'])->name('type-content.getDropdownListById');
});

// ЭЛЕМЕНТЫ КОНТЕНТА
Route::middleware(['auth:web'])->group(function () {
    Route::get('/element-content/getElementContentID/{id}', [ElementContentController::class, 'getElementContentID']);
    Route::get('/element-content/update-fields/{id}', [ElementContentController::class, 'updateFields']);
    Route::get('/element-content/all-version-element-content/{id}', [ElementContentController::class, 'getAllVersion'])->name('element-content.all-version');
    Route::get('/element-content/getAllVersionElementContent/{id}', [ElementContentController::class, 'getAllVersionElementContent']);
    Route::get('/element-content/api-url/{apiUrl}', [ElementContentController::class, 'getApiUrl']);
    Route::get('element-content/findElementContentID/{id}', [ElementContentController::class, 'findElementContentID']);
    Route::get('element-content/findElementContentAll', [ElementContentController::class, 'findElementContentAll']);
    Route::get('/element-content/{type_content_id}', [ElementContentController::class, 'index'])->name('element-content.index');
    Route::get('/element-content', [ElementContentController::class, 'indexAll'])->name('element-content.indexAll');
    Route::post('/element-content/store/{type_content_id}', [ElementContentController::class, 'store'])->name('element-content.store');
    Route::get('/element-content/{id}/edit', [ElementContentController::class, 'edit'])->name('element-content.edit');
    Route::post('/element-content/{id}', [ElementContentController::class, 'update'])->name('element-content.update');
    Route::get('/element-content/{id}/del', [ElementContentController::class, 'destroy'])->name('element-content.destroy');
    Route::get('/element-content/{id}/{parameter}', [ElementContentController::class, 'createNewVersion'])->name('element-content.create-new-version');
    Route::put('/element-content/enter/{id}/saveDraft', [ElementContentController::class, 'saveDraft'])->name('element-content.saveDraft');
    Route::delete('/element-content/{id}', [ElementContentController::class, 'destroy'])->name('element-content.destroy');
    Route::post('/upload-image', [ElementContentController::class, 'uploadImage'])->name('element-content.upload-image');
    Route::get('/element-content/new-version/{id}/{parametr}', [ElementContentController::class, 'createNewVersion']);

    Route::get('/api/v1/element-content/{typeContentApiUrl}/{typeVersion}/{elementContentApiUrl}/{elementVersion}', [ElementContentController::class, 'getApiElement']);
    Route::get('/api/v1/element-content/{typeContentApiUrl}/{typeVersion}/{elementContentApiUrl}/{elementVersion}/explode', [ElementContentController::class, 'getApiElementExplode']);
});
