<?php

use App\Http\Controllers\TechController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//из библиотеки jwt
Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

});

Route::group(['middleware' => 'auth:api'], function ($router) {
    Route::get('/dictionary/findDictionary/', [App\Http\Controllers\DictionaryController::class, 'index']);
    Route::post('/dictionary/createdDictionary/', [App\Http\Controllers\DictionaryController::class, 'store'])->middleware('auth:api');
    Route::put('/dictionary/updatedDictionary/{id}', [App\Http\Controllers\DictionaryController::class, 'update'])->middleware('auth:api');
    Route::delete('/dictionary/deletedDictionary/{id}', [App\Http\Controllers\DictionaryController::class, 'destroy'])->middleware('auth:api');
});

Route::group(['middleware' => 'auth:api'], function ($router) {
    Route::get('/dictionary/findElementDictionaryID/{id}', [App\Http\Controllers\DictionaryElementController::class, 'findElementDictionaryID']);//получение элементов по dictionary_id
    Route::get('/dictionary/findElementDictionaryCode/{code}', [App\Http\Controllers\DictionaryElementController::class, 'indexCode']);//получение элементов по dictionary_id
    Route::post('/dictionary/createdElementDictionary/', [App\Http\Controllers\DictionaryElementController::class, 'store'])->middleware('auth:api');
    Route::put('/dictionary/updatedElementDictionary/{id}', [App\Http\Controllers\DictionaryElementController::class, 'update'])->middleware('auth:api');
    Route::delete('/dictionary/deletedElementDictionary/{id}', [App\Http\Controllers\DictionaryElementController::class, 'destroy'])->middleware('auth:api');
});

Route::get('/template/getListTypeContent/', [App\Http\Controllers\TypeContentController::class, 'getListTypeContent']);
Route::group(['middleware' => 'auth:api'], function ($router) {
    Route::post('/template/createTypeContent/', [App\Http\Controllers\TypeContentController::class, 'store'])->middleware('auth:api');
    Route::put('/template/updateTypeContent/{id}', [App\Http\Controllers\TypeContentController::class, 'update'])->middleware('auth:api');
    Route::delete('/template/deleteTypeContent/{id}', [App\Http\Controllers\TypeContentController::class, 'destroy'])->middleware('auth:api');
    Route::get('/template/getAllVersionTypeContent/{$id}', [App\Http\Controllers\TypeContentController::class, 'getAllVersionTypeContent'])->middleware('auth:api');
    
});
