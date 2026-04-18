<?php

namespace App\Areas\Main\Application\Data;

use App\Areas\Main\Domain\Entities\PersonaFisica;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PersonaFisicaData extends Data
{
    public function __construct(
        public string $codiceFiscale,
        public string $cognome,
        public string $nome,
        public string $sesso,
        public Carbon $dataNascita,
        public string $comuneNascitaCodice,
        public string $comuneNascitaDescrizione,
    ) {}

    public static function fromEntity(PersonaFisica $pf): self
    {
        return new self(
            codiceFiscale: $pf->codiceFiscale,
            cognome: $pf->cognome,
            nome: $pf->nome,
            sesso: $pf->sesso,
            dataNascita: $pf->dataNascita,
            comuneNascitaCodice: $pf->comuneNascitaCodice,
            comuneNascitaDescrizione: $pf->comuneNascitaDescrizione,
        );
    }
}
