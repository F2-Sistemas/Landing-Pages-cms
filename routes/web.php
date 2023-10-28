<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\IndexController;

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

// Se tiver uma home, mude isso para retornar a view/controller da home
Route::get('/', IndexController::class)->name('index');

Route::get(
    '/login',
    fn () => redirect()->route('filament.admin.auth.login')
)->name('login');
