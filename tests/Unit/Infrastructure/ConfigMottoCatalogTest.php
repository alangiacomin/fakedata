<?php

use App\Areas\Main\Motto\Infrastructure\Catalogs\ConfigMottoCatalog;

it('legge i gruppi dei motti dalla configurazione', function () {
    config()->set('mottos.groups', [
        'team' => ['Prima funziona, poi si rifinisce.'],
    ]);

    $catalog = new ConfigMottoCatalog();

    expect($catalog->groups())
        ->toBe([
            'team' => ['Prima funziona, poi si rifinisce.'],
        ]);
});

it('ritorna un array vuoto se la configurazione dei gruppi non è valida', function () {
    config()->set('mottos.groups', 'non valido');

    $catalog = new ConfigMottoCatalog();

    expect($catalog->groups())
        ->toBe([]);
});
