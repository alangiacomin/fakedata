<?php

use App\Areas\Main\Application\Contracts\PersonaFisicaFactoryInterface;
use App\Areas\Main\Application\Data\PersonaFisicaData;
use App\Areas\Main\Application\Services\PersonaFisicaService;
use App\Areas\Main\Domain\Entities\PersonaFisica;
use Carbon\Carbon;

afterEach(function () {
    Mockery::close();
});

it('restituisce un dto mappato dall\'entità generata dalla factory', function () {
    $entity = new PersonaFisica(
        cognome: 'Rossi',
        nome: 'Marco',
        sesso: 'M',
        dataNascita: Carbon::create(1985, 4, 9),
        codiceComune: 'H501',
    );

    $factory = Mockery::mock(PersonaFisicaFactoryInterface::class);
    $factory->shouldReceive('genera')->once()->andReturn($entity);

    $service = new PersonaFisicaService($factory);
    $result = $service->random();

    expect($result)
        ->toBeInstanceOf(PersonaFisicaData::class)
        ->and($result->codiceFiscale)->toBe($entity->codiceFiscale)
        ->and($result->cognome)->toBe($entity->cognome)
        ->and($result->nome)->toBe($entity->nome)
        ->and($result->sesso)->toBe($entity->sesso)
        ->and($result->dataNascita->equalTo($entity->dataNascita))->toBeTrue()
        ->and($result->comuneNascitaCodice)->toBe($entity->comuneNascitaCodice)
        ->and($result->comuneNascitaDescrizione)->toBe($entity->comuneNascitaDescrizione);
});
