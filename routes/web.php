<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;

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
    return auth()->check()
        ? redirect()->to(backpack_url('dashboard'))
        : redirect()->to(backpack_url('login'));
});

Route::fallback(function () {
    return auth()->check()
        ? redirect()->to(backpack_url('dashboard'))
        : redirect()->to(backpack_url('login'));
});
