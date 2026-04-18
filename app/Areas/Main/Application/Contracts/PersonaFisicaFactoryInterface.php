<?php

namespace App\Areas\Main\Application\Contracts;

use App\Areas\Main\Domain\Entities\PersonaFisica;

interface PersonaFisicaFactoryInterface
{
    public function genera(): PersonaFisica;
}
