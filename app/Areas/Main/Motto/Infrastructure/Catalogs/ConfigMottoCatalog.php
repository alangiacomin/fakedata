<?php

namespace App\Areas\Main\Motto\Infrastructure\Catalogs;

use App\Areas\Main\Motto\Application\Contracts\MottoCatalogInterface;

class ConfigMottoCatalog implements MottoCatalogInterface
{
    public function groups(): array
    {
        $groups = config('mottos.groups', []);

        return is_array($groups) ? $groups : [];
    }
}
