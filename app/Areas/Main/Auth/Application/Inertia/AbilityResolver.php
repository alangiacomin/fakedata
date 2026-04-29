<?php

namespace App\Areas\Main\Auth\Application\Inertia;

use App\Shared\Infrastructure\Enums\GateEnum;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;

class AbilityResolver
{
    public function forUser(?Authenticatable $user): array
    {
        if (!$user) {
            return [];
        }

        $gate = Gate::forUser($user);

        return collect(GateEnum::cases())
            ->filter(fn ($ability) => $gate->allows($ability))
            ->map(fn ($ability) => $ability->value)
            ->values()
            ->all();
    }
}
