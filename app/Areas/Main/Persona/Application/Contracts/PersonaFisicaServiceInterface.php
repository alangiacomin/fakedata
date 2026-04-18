<?php

namespace App\Areas\Main\Persona\Application\Contracts;

use App\Areas\Main\Persona\Application\Data\PersonaFisicaData;

interface PersonaFisicaServiceInterface
{
    public function random(): PersonaFisicaData;
}
