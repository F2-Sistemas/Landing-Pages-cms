<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\PageController;

Route::prefix('p')
    ->name('pages.')
    ->group(function () {
        Route::get('{page}', [PageController::class, 'show'])
            ->whereAlphaNumeric('page')
            ->name('show');
    });
