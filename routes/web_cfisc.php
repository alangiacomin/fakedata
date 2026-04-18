<?php

use App\Areas\Main\Persona\Presentation\Http\Controllers\FallbackController;
use App\Areas\Main\Persona\Presentation\Http\Controllers\PersonaFisicaController;
use Illuminate\Support\Facades\Route;

localeRoutes(function () {
    Route::prefix('persona-fisica')->name('persona-fisica.')->group(function () {
        Route::get('/random', [PersonaFisicaController::class, 'random'])->name('random');

        Route::get('/{any}', [FallbackController::class, 'notFound'])->name('not.found');
    });
});
