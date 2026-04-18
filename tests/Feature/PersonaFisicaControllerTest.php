<?php

use App\Areas\Main\Application\Contracts\PersonaFisicaServiceInterface;
use App\Areas\Main\Application\Data\PersonaFisicaData;
use App\Areas\Main\Presentation\Http\Controllers\FallbackController;
use App\Areas\Main\Presentation\Http\Controllers\PersonaFisicaController;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

it('restituisce una persona fisica random in formato json dal controller', function () {
    $data = new PersonaFisicaData(
        codiceFiscale: 'RSSMRC85D09H501K',
        cognome: 'Rossi',
        nome: 'Marco',
        sesso: 'M',
        dataNascita: Carbon::create(1985, 4, 9),
        comuneNascitaCodice: 'H501',
        comuneNascitaDescrizione: 'ROMA',
    );

    $service = Mockery::mock(PersonaFisicaServiceInterface::class);
    $service->shouldReceive('random')->once()->andReturn($data);

    $controller = new PersonaFisicaController($service);
    $response = $controller->random();

    expect($response->getStatusCode())->toBe(200);

    $json = $response->getData(true);

    expect($json)
        ->toHaveKeys([
            'codiceFiscale',
            'cognome',
            'nome',
            'sesso',
            'dataNascita',
            'comuneNascitaCodice',
            'comuneNascitaDescrizione',
        ])
        ->and($json['codiceFiscale'])->toHaveLength(16)
        ->and($json['sesso'])->toBeIn(['M', 'F']);
});

it('fallback controller ritorna 404', function () {
    expect(fn () => (new FallbackController())->notFound())
        ->toThrow(NotFoundHttpException::class);
});
