<?php

use App\Areas\Main\Motto\Application\Contracts\MottoCatalogInterface;
use App\Areas\Main\Motto\Application\Services\RandomMottoService;

it('restituisce il fallback quando il catalogo non ha gruppi validi', function () {
    $catalog = new class implements MottoCatalogInterface
    {
        public function groups(): array
        {
            return [
                'vuoto' => [],
            ];
        }
    };

    $service = new RandomMottoService($catalog);

    expect($service->pick())
        ->toBe('Ogni giorno è buono per fare bene le cose semplici.');
});

it('seleziona un motto da un gruppo non vuoto', function () {
    $catalog = new class implements MottoCatalogInterface
    {
        public function groups(): array
        {
            return [
                'vuoto' => [],
                'valido' => ['Solo sostanza.'],
            ];
        }
    };

    $service = new RandomMottoService($catalog);

    expect($service->pick())
        ->toBe('Solo sostanza.');
});
