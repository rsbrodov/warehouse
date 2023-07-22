<?php

use App\Http\Controllers\ElementContentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

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
Route::get('/api/v1/element-content/{typeContentApiUrl}/{typeVersionMajor}/{typeVersionMinor}/{elementContentApiUrl}/{versionMajor}/{versionMinor}', [ElementContentController::class, 'getApiElement']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/dictionary/test/', [App\Http\Controllers\DictionaryController::class, 'test']);

//из библиотеки jwt
Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

});

Route::group(['middleware' => 'auth:api'], function ($router) {
    Route::get('/dictionary/findDictionary/', [App\Http\Controllers\DictionaryController::class, 'findDictionary']);
    Route::post('/dictionary/createdDictionary/', [App\Http\Controllers\DictionaryController::class, 'store']);
    Route::put('/dictionary/updatedDictionary/{id}', [App\Http\Controllers\DictionaryController::class, 'update']);
    Route::delete('/dictionary/deletedDictionary/{id}', [App\Http\Controllers\DictionaryController::class, 'destroy']);
});

Route::group(['middleware' => 'auth:api'], function ($router) {
    Route::get('/dictionary/findElementDictionaryID/{id}', [App\Http\Controllers\DictionaryElementController::class, 'findElementDictionaryID']);//получение элементов по dictionary_id
    Route::get('/dictionary/findElementDictionaryCode/{code}', [App\Http\Controllers\DictionaryElementController::class, 'indexCode']);//получение элементов по dictionary_id
    Route::post('/dictionary/createdElementDictionary/', [App\Http\Controllers\DictionaryElementController::class, 'store']);
    Route::put('/dictionary/updatedElementDictionary/{id}', [App\Http\Controllers\DictionaryElementController::class, 'update']);
    Route::delete('/dictionary/deletedElementDictionary/{id}', [App\Http\Controllers\DictionaryElementController::class, 'destroy']);
});

Route::get('/template/getListTypeContent/', [App\Http\Controllers\TypeContentController::class, 'getListTypeContent']);
Route::group(['middleware' => 'auth:api'], function ($router) {
    Route::post('/template/createTypeContent/', [App\Http\Controllers\TypeContentController::class, 'store'])->middleware('auth:api');
    Route::put('/template/updateTypeContent/{id}', [App\Http\Controllers\TypeContentController::class, 'update'])->middleware('auth:api');
    Route::delete('/template/deleteTypeContent/{id}', [App\Http\Controllers\TypeContentController::class, 'destroy'])->middleware('auth:api');
    Route::get('/template/getAllVersionTypeContent/{idGlobal}', [App\Http\Controllers\TypeContentController::class, 'getAllVersionTypeContent'])->middleware('auth:api');

});
Route::get('/type-content/find-all-element-body/{id}', [App\Http\Controllers\TypeContentController::class, 'allElementByTypeContent']);
