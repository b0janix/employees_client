<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Route::get('/', [HomeController::class, 'index']);

Route::post(
    '/redirect',
    [
        HomeController::class,
        'redirect'
    ]
)->name('redirect');

Route::post(
    '/getEmployees',
    [
        HomeController::class,
        'getEmployees'
    ]
)->name('getEmployees');

Route::get(
    '/callback',
    [
        HomeController::class,
        'callback'
    ]
)->name('employeesCallback');

Route::post(
    '/truncateTokens',
    [
        HomeController::class,
        'deleteTokens'
    ]
)->name('truncateTokens');
