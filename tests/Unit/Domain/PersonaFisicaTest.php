<?php

use App\Areas\Main\Domain\Entities\PersonaFisica;
use Carbon\Carbon;

it('calcola le componenti principali del codice fiscale per un uomo', function () {
    $persona = new PersonaFisica(
        cognome: 'rossi',
        nome: 'marco',
        sesso: 'M',
        dataNascita: Carbon::create(1985, 4, 9),
        codiceComune: 'H501',
    );

    expect($persona->codiceFiscale)
        ->toHaveLength(16)
        ->and(substr($persona->codiceFiscale, 0, 3))->toBe('RSS')
        ->and(substr($persona->codiceFiscale, 3, 3))->toBe('MRC')
        ->and(substr($persona->codiceFiscale, 6, 2))->toBe('85')
        ->and(substr($persona->codiceFiscale, 8, 1))->toBe('D')
        ->and(substr($persona->codiceFiscale, 9, 2))->toBe('09')
        ->and(substr($persona->codiceFiscale, 11, 4))->toBe('H501')
        ->and($persona->comuneNascitaDescrizione)->toBe('ROMA');
});

it('applica la regola del +40 al giorno per il sesso femminile', function () {
    $persona = new PersonaFisica(
        cognome: 'rossi',
        nome: 'maria',
        sesso: 'F',
        dataNascita: Carbon::create(1985, 4, 9),
        codiceComune: 'H501',
    );

    expect(substr($persona->codiceFiscale, 9, 2))->toBe('49');
});

it('usa la codifica nome speciale quando ci sono almeno quattro consonanti', function () {
    $persona = new PersonaFisica(
        cognome: 'verdi',
        nome: 'christopher',
        sesso: 'M',
        dataNascita: Carbon::create(1991, 11, 3),
        codiceComune: 'F205',
    );

    expect(substr($persona->codiceFiscale, 3, 3))->toBe('CRS');
});

it('usa il codice come descrizione quando il belfiore non è censito', function () {
    $persona = new PersonaFisica(
        cognome: 'bianchi',
        nome: 'luca',
        sesso: 'M',
        dataNascita: Carbon::create(1999, 1, 1),
        codiceComune: 'Z999',
    );

    expect($persona->comuneNascitaDescrizione)->toBe('Z999')
        ->and(substr($persona->codiceFiscale, 11, 4))->toBe('Z999');
});
