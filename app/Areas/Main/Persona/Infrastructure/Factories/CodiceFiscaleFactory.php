<?php

namespace App\Areas\Main\Persona\Infrastructure\Factories;

use App\Areas\Main\Persona\Application\Contracts\PersonaFisicaFactoryInterface;
use App\Areas\Main\Persona\Domain\Entities\CodiceBelfiore;
use App\Areas\Main\Persona\Domain\Entities\PersonaFisica;
use Carbon\Carbon;

class CodiceFiscaleFactory implements PersonaFisicaFactoryInterface
{
    public function genera(): PersonaFisica
    {
        $sesso = fake()->randomElement(['M', 'F']);
        $comuneNascita = fake()->randomElement(CodiceBelfiore::allCodici());

        return new PersonaFisica(
            fake()->lastName(),
            $sesso == 'M' ? fake()->firstNameMale() : fake()->firstNameFemale(),
            $sesso,
            Carbon::instance(fake()->dateTimeBetween(now()->subYears(120), now()->subDay()))->startOfDay(),
            $comuneNascita,
        );
    }
}
