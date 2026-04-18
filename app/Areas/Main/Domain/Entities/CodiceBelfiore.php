<?php

namespace App\Areas\Main\Domain\Entities;

class CodiceBelfiore
{
    private const array COMUNI = [
        'H501' => 'ROMA',
        'F205' => 'MILANO',
        'L219' => 'TORINO',
        'D612' => 'FIRENZE',
        'G273' => 'PALERMO',
        'B157' => 'BOLOGNA',
        'C351' => 'CATANIA',
        'E506' => 'GENOVA',
        'A944' => 'BARI',
        'L736' => 'VENEZIA',
        'G478' => 'PADOVA',
        'L781' => 'VERONA',
        'I452' => 'NAPOLI',
        'H224' => 'PISA',
        'F839' => 'MODENA',
        'D643' => 'FERRARA',
        'C933' => 'COMO',
        'A794' => 'BERGAMO',
        'B963' => 'BRESCIA',
        'F704' => 'MONZA',
        'H620' => 'RIMINI',
        'H294' => 'PESCARA',
        'A345' => 'ANCONA',
        'I726' => 'PERUGIA',
        'E463' => 'GROSSETO',
        'L378' => 'TRENTO',
        'L424' => 'TRIESTE',
        'A662' => 'AOSTA',
        'H163' => 'PIACENZA',
        'E897' => 'LECCE',
        'C352' => 'CATANZARO',
        'D086' => 'COSENZA',
        'F537' => 'MESSINA',
        'L049' => 'TARANTO',
        'G942' => 'POTENZA',
        'F158' => 'MATERA',
        'A783' => 'BENEVENTO',
        'E716' => 'LATINA',
        'G888' => 'PRATO',
        'H703' => 'RAVENNA',
        'F023' => 'MANTOVA',
        'C957' => 'CREMONA',
        'L483' => 'UDINE',
        'L840' => 'VICENZA',
        'H282' => 'PESARO',
        'E098' => 'IMOLA',
        'A509' => 'AREZZO',
        'I690' => 'PORDENONE',
        'E379' => 'GORIZIA',
        'L182' => 'TERNI',
    ];

    private const array STATI_ESTERI = [
        'Z110' => 'FRANCIA',
        'Z111' => 'GERMANIA',
        'Z112' => 'GRECIA',
        'Z113' => 'IRLANDA',
        'Z114' => 'ISLANDA',
        'Z115' => 'LETTONIA',
        'Z116' => 'LIECHTENSTEIN',
        'Z117' => 'LITUANIA',
        'Z118' => 'LUSSEMBURGO',
        'Z119' => 'MALTA',
    ];

    public static function allCodici(): array
    {
        return array_keys(self::COMUNI + self::STATI_ESTERI);
    }

    public static function getDescrizione(string $codice): string
    {
        return self::COMUNI[$codice]
            ?? self::STATI_ESTERI[$codice]
            ?? $codice;
    }
}
