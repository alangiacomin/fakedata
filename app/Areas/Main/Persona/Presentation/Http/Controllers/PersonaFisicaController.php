<?php

namespace App\Areas\Main\Persona\Presentation\Http\Controllers;

use App\Areas\Main\Persona\Application\Contracts\PersonaFisicaServiceInterface;
use App\Areas\Main\Persona\Application\Data\CalcolaCodiceFiscaleInputData;
use App\Areas\Main\Persona\Domain\Entities\LuoghiNascita;
use App\Areas\Main\Persona\Presentation\Http\Requests\CalcolaCodiceFiscaleRequest;
use App\Cqrs\App\Presentation\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Inertia\Response;
use Inertia\ResponseFactory;
use InvalidArgumentException;

class PersonaFisicaController extends Controller
{
    public function __construct(public PersonaFisicaServiceInterface $personaFisicaService) {}

    #[Middleware('not_banned')]
    public function codiceFiscale(Request $request): Response|ResponseFactory
    {
        $persona = null;
        if ($request->session()->has('persona')) {
            $persona = $request->session()->get('persona');
        }

        return inertia('App/CodiceFiscale/CodiceFiscale', [
            'luoghiNascitaOptions' => LuoghiNascita::all(),
            'persona' => $persona,
        ]);
    }

    public function generaCodiceFiscale(): RedirectResponse
    {
        return back()
            ->with('persona', $this->personaFisicaService->random())
            ->with('success', 'Anagrafica generata correttamente.');
    }

    public function calcolaCodiceFiscale(CalcolaCodiceFiscaleRequest $request): RedirectResponse
    {
        try {
            $persona = $this->personaFisicaService->calcola(new CalcolaCodiceFiscaleInputData(
                cognome: (string) $request->string('cognome'),
                nome: (string) $request->string('nome'),
                sesso: (string) $request->string('sesso'),
                dataNascita: Carbon::createFromFormat('d/m/Y', (string) $request->string('dataNascita'))->startOfDay(),
                luogoNascita: (string) $request->string('comuneNascitaDescrizione'),
            ));

            return back()->with('persona', $persona)->with('success', 'Codice fiscale calcolato correttamente.');
        } catch (InvalidArgumentException $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
