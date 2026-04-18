<?php

namespace App\Areas\Main\Persona\Application\Services;

use App\Areas\Main\Persona\Application\Contracts\PersonaFisicaFactoryInterface;
use App\Areas\Main\Persona\Application\Contracts\PersonaFisicaServiceInterface;
use App\Areas\Main\Persona\Application\Data\PersonaFisicaData;

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
}
