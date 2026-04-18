<?php

namespace App\Areas\Main\Domain\Entities;

use Carbon\Carbon;

class PersonaFisica
{
    private const array MESI = [
        1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D',
        5 => 'E', 6 => 'H', 7 => 'L', 8 => 'M',
        9 => 'P', 10 => 'R', 11 => 'S', 12 => 'T',
    ];

    public private(set) string $codiceFiscale;

    public private(set) string $cognome;

    public private(set) string $nome;

    public private(set) Carbon $dataNascita;

    public private(set) string $sesso;

    public private(set) string $comuneNascitaCodice;

    public private(set) string $comuneNascitaDescrizione;

    public function __construct(
        string $cognome,
        string $nome,
        string $sesso,
        Carbon $dataNascita,
        string $codiceComune
    ) {
        $cognome = ucfirst($cognome);
        $nome = ucfirst($nome);
        $this->cognome = $cognome;
        $this->nome = $nome;
        $this->sesso = $sesso;
        $this->dataNascita = $dataNascita;
        $this->comuneNascitaCodice = $codiceComune;
        $this->comuneNascitaDescrizione = CodiceBelfiore::getDescrizione($codiceComune);
        $this->codiceFiscale = $this->calcola($cognome, $nome, $sesso, $dataNascita, $codiceComune);
    }

    private function calcola(
        string $cognome,
        string $nome,
        string $sesso,
        Carbon $dataNascita,
        string $codiceComune
    ): string {
        $cognomeCod = $this->codificaStringa($cognome);
        $nomeCod = $this->codificaNome($nome);

        $anno = substr($dataNascita->format('Y'), -2);
        $mese = self::MESI[(int) $dataNascita->format('m')];

        $giorno = (int) $dataNascita->format('d');
        if ($sesso === 'F') {
            $giorno += 40;
        }

        $giorno = str_pad($giorno, 2, '0', STR_PAD_LEFT);

        $parziale = strtoupper(
            $cognomeCod.
            $nomeCod.
            $anno.
            $mese.
            $giorno.
            $codiceComune
        );

        return $parziale.$this->calcolaControllo($parziale);
    }

    private function codificaStringa(string $stringa): string
    {
        $consonanti = $this->estraiConsonanti($stringa);
        $vocali = $this->estraiVocali($stringa);

        return substr($consonanti.$vocali.'XXX', 0, 3);
    }

    private function estraiConsonanti(string $stringa): string
    {
        return preg_replace('/[^BCDFGHJKLMNPQRSTVWXYZ]/i', '', strtoupper($stringa)) ?? '';
    }

    private function estraiVocali(string $stringa): string
    {
        return preg_replace('/[^AEIOU]/i', '', strtoupper($stringa)) ?? '';
    }

    private function codificaNome(string $nome): string
    {
        $consonanti = $this->estraiConsonanti($nome);

        if (strlen($consonanti) >= 4) {
            return $consonanti[0].$consonanti[2].$consonanti[3];
        }

        return $this->codificaStringa($nome);
    }

    private function calcolaControllo($codice): string
    {
        $pari = [
            '0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9,
            'A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4, 'F' => 5, 'G' => 6, 'H' => 7, 'I' => 8, 'J' => 9,
            'K' => 10, 'L' => 11, 'M' => 12, 'N' => 13, 'O' => 14, 'P' => 15, 'Q' => 16, 'R' => 17, 'S' => 18, 'T' => 19,
            'U' => 20, 'V' => 21, 'W' => 22, 'X' => 23, 'Y' => 24, 'Z' => 25,
        ];

        $dispari = [
            '0' => 1, '1' => 0, '2' => 5, '3' => 7, '4' => 9, '5' => 13, '6' => 15, '7' => 17, '8' => 19, '9' => 21,
            'A' => 1, 'B' => 0, 'C' => 5, 'D' => 7, 'E' => 9, 'F' => 13, 'G' => 15, 'H' => 17, 'I' => 19, 'J' => 21,
            'K' => 2, 'L' => 4, 'M' => 18, 'N' => 20, 'O' => 11, 'P' => 3, 'Q' => 6, 'R' => 8, 'S' => 12, 'T' => 14,
            'U' => 16, 'V' => 10, 'W' => 22, 'X' => 25, 'Y' => 24, 'Z' => 23,
        ];

        $somma = 0;

        for ($i = 0; $i < strlen($codice); $i++) {
            $char = $codice[$i];
            if (($i + 1) % 2 === 0) {
                $somma += $pari[$char];
            } else {
                $somma += $dispari[$char];
            }
        }

        return chr(($somma % 26) + 65);
    }
}
