<?php

namespace App\Areas\Main\Application\Contracts;

use App\Areas\Main\Application\Data\PersonaFisicaData;

interface PersonaFisicaServiceInterface
{
    public function random(): PersonaFisicaData;
}
