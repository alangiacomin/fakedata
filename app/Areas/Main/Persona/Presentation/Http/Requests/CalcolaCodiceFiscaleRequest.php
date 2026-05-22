<?php

namespace App\Areas\Main\Persona\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalcolaCodiceFiscaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cognome' => ['required', 'string'],
            'nome' => ['required', 'string'],
            'sesso' => ['required', 'in:M,F'],
            'dataNascita' => ['required', 'date_format:d/m/Y'],
            'comuneNascitaDescrizione' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'cognome.required' => 'Compila il cognome.',
            'nome.required' => 'Compila il nome.',
            'sesso.required' => 'Seleziona il sesso.',
            'dataNascita.required' => 'Compila la data di nascita.',
            'dataNascita.date_format' => 'La data di nascita deve essere nel formato gg/mm/aaaa.',
            'comuneNascitaDescrizione.required' => 'Compila il luogo di nascita.',
        ];
    }
}
