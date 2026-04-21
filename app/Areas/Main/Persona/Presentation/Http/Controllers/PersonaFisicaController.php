<?php

namespace App\Areas\Main\Persona\Presentation\Http\Controllers;

use App\Areas\Main\Persona\Application\Contracts\PersonaFisicaServiceInterface;
use App\Cqrs\App\Presentation\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Inertia\Response;
use Inertia\ResponseFactory;

class PersonaFisicaController extends Controller
{
    public function __construct(public PersonaFisicaServiceInterface $personaFisicaService) {}

    #[Middleware('not_banned')]
    public function codiceFiscale(Request $request): Response|ResponseFactory
    {
        return inertia('App/CodiceFiscale/CodiceFiscale', [
            'persona' => $request->boolean('generate')
                ? $this->personaFisicaService->random()
                : null,
        ]);
    }

    public function random()
    {
        $pf = $this->personaFisicaService->random();

        return response()->json($pf);

        return inertia('App/Home/Home', [
            'pf' => $pf,
        ]);
    }
}
