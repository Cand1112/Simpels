<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn () => redirect('/admin'));
Route::get('/login', fn () => redirect('/admin/login'))->name('login');

Route::get('/delete-location', function () {
    \App\Models\Province::truncate();
    \App\Models\District::truncate();
    \App\Models\Subdistrict::truncate();

    return 'ok';
});

Route::get('/create-location', function () {
    \App\Jobs\CreateLocationJob::dispatch();

    return 'ok';
});
