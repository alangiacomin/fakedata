<?php

namespace App\Areas\Main\Motto\Application\Services;

use App\Areas\Main\Motto\Application\Contracts\MottoCatalogInterface;

class RandomMottoService
{
    public function __construct(
        private readonly MottoCatalogInterface $catalog,
    ) {}

    public function pick(): string
    {
        $groups = array_values(array_filter(
            $this->catalog->groups(),
            static fn (array $group): bool => $group !== [],
        ));

        if ($groups === []) {
            return 'Ogni giorno è buono per fare bene le cose semplici.';
        }

        $group = $groups[array_rand($groups)];

        return $group[array_rand($group)];
    }
}
