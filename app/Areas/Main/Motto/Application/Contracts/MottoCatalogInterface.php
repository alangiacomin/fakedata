<?php

namespace App\Areas\Main\Motto\Application\Contracts;

interface MottoCatalogInterface
{
    /**
     * @return array<string, array<int, string>>
     */
    public function groups(): array;
}
