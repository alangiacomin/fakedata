<?php

namespace App\Areas\Main\Persona\Application\Services;

use App\Areas\Main\Persona\Application\Contracts\PersonaFisicaFactoryInterface;
use App\Areas\Main\Persona\Application\Contracts\PersonaFisicaServiceInterface;
use App\Areas\Main\Persona\Application\Data\CalcolaCodiceFiscaleInputData;
use App\Areas\Main\Persona\Application\Data\PersonaFisicaData;
use App\Areas\Main\Persona\Domain\Entities\CodiceBelfiore;
use App\Areas\Main\Persona\Domain\Entities\PersonaFisica;
use InvalidArgumentException;

readonly class PersonaFisicaService implements PersonaFisicaServiceInterface
{
    public function __construct(
        private PersonaFisicaFactoryInterface $personaFisicaFactory,
    ) {}

    public function random(): PersonaFisicaData
    {
        $pf = $this->personaFisicaFactory->genera();

        return PersonaFisicaData::fromEntity($pf);
    }

    public function calcola(CalcolaCodiceFiscaleInputData $input): PersonaFisicaData
    {
        $codiceComune = CodiceBelfiore::getCodiceByDescrizione($input->luogoNascita);

        if (!$codiceComune) {
            throw new InvalidArgumentException('Il luogo di nascita selezionato non è valido.');
        }

        $persona = new PersonaFisica(
            $input->cognome,
            $input->nome,
            $input->sesso,
            $input->dataNascita,
            $codiceComune,
        );

        return PersonaFisicaData::fromEntity($persona);
    }
}
