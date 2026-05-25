<?php

use App\Areas\Main\Persona\Application\Contracts\PersonaFisicaServiceInterface;
use App\Areas\Main\Persona\Application\Data\PersonaFisicaData;
use App\Areas\Main\Persona\Presentation\Http\Controllers\FallbackController;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

it('genera una persona fisica random e torna indietro con flash data', function () {
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

    $this->app->instance(PersonaFisicaServiceInterface::class, $service);

    $this
        ->withSession(['_token' => 'test-token'])
        ->from(route('persona-fisica.codice-fiscale'))
        ->post(route('persona-fisica.codice-fiscale.genera'), ['_token' => 'test-token'])
        ->assertRedirect(route('persona-fisica.codice-fiscale'))
        ->assertSessionHas('persona', $data)
        ->assertSessionHas('success', 'Anagrafica generata correttamente.');
});

it('fallback controller ritorna 404', function () {
    expect(fn () => new FallbackController()->notFound())
        ->toThrow(NotFoundHttpException::class);
});
