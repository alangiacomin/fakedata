<?php

namespace App\Areas\Main\Persona\Application\Contracts;

use App\Areas\Main\Persona\Domain\Entities\PersonaFisica;

interface PersonaFisicaFactoryInterface
{
    public function genera(): PersonaFisica;
}
