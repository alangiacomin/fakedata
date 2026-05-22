<?php

namespace App\Areas\Main\Persona\Application\Data;

use Carbon\Carbon;

readonly class CalcolaCodiceFiscaleInputData
{
    public function __construct(
        public string $cognome,
        public string $nome,
        public string $sesso,
        public Carbon $dataNascita,
        public string $luogoNascita,
    ) {}
}
