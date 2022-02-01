<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TechController;
use App\Http\Controllers\YurkController;
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
Route::get('/yurk/link/{data}', [YurkController::class, 'linkHandler'])->name('link.handler');

