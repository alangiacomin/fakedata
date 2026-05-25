<?php

use App\Areas\Main\Persona\Presentation\Http\Controllers\PersonaFisicaController;
use Illuminate\Support\Facades\Route;

localeRoutes(function () {
    Route::prefix('persona-fisica')->name('persona-fisica.')->group(function () {
        Route::get('/codice-fiscale', [PersonaFisicaController::class, 'codiceFiscale'])->name('codice-fiscale');
        Route::post('/codice-fiscale/genera', [PersonaFisicaController::class, 'generaCodiceFiscale'])->name('codice-fiscale.genera');
        Route::post('/codice-fiscale/calcola', [PersonaFisicaController::class, 'calcolaCodiceFiscale'])->name('codice-fiscale.calcola');
    });
});
