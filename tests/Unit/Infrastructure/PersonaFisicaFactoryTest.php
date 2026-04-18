<?php

use App\Areas\Main\Persona\Domain\Entities\CodiceBelfiore;
use App\Areas\Main\Persona\Domain\Entities\PersonaFisica;
use App\Areas\Main\Persona\Infrastructure\Factories\PersonaFisicaFactory;

it('genera una persona fisica valida', function () {
    $factory = new PersonaFisicaFactory();
    $persona = $factory->genera();

    expect($persona)
        ->toBeInstanceOf(PersonaFisica::class)
        ->and($persona->sesso)->toBeIn(['M', 'F'])
        ->and($persona->codiceFiscale)->toHaveLength(16)
        ->and($persona->comuneNascitaCodice)->toBeIn(CodiceBelfiore::allCodici())
        ->and($persona->comuneNascitaDescrizione)->toBe(CodiceBelfiore::getDescrizione($persona->comuneNascitaCodice))
        ->and($persona->dataNascita->isStartOfDay())->toBeTrue()
        ->and($persona->dataNascita->between(now()->subYears(120)->startOfDay(), now()->subDay()->endOfDay()))->toBeTrue();
});
