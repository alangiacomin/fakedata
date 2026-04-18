<?php

namespace App\Areas\Main\Persona\Presentation\Http\Controllers;

use App\Areas\Main\Persona\Application\Contracts\PersonaFisicaServiceInterface;
use App\Cqrs\App\Presentation\Http\Controllers\Controller;

class PersonaFisicaController extends Controller
{
    public function __construct(public PersonaFisicaServiceInterface $personaFisicaService) {}

    public function random()
    {
        $pf = $this->personaFisicaService->random();

        return response()->json($pf);

        return inertia('App/Home/Home', [
            'pf' => $pf,
        ]);
    }
}
