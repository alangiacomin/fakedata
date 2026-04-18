<?php

use App\Areas\Main\Persona\Presentation\Http\Controllers\FallbackController;
use Illuminate\Support\Facades\Route;

if (!function_exists('localeRoutes')) {
    function localeRoutes($localizedRoutes): void
    {
        Route::prefix('{locale}')->name('localized.')->group($localizedRoutes);
        $localizedRoutes();
    }
}

include_once __DIR__.'/web_cfisc.php';

localeRoutes(function () {
    Route::get('/', [FallbackController::class, 'app'])->name('home');
    Route::get('/codice-fiscale', [FallbackController::class, 'codiceFiscale'])->name('codice.fiscale');
    Route::get('/{any}', [FallbackController::class, 'notFound'])->name('not.found');
});
