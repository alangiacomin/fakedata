<?php

namespace App\Infrastructure\Middleware;

use App\Areas\Main\Auth\Application\Data\UserData;
use App\Areas\Main\Auth\Application\Inertia\AbilityResolver;
use App\Areas\Main\Auth\Infrastructure\Mappers\UserItemMapper;
use App\Areas\Main\Motto\Application\Services\RandomMottoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    // protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    // public function version(Request $request): ?string
    // {
    //     return parent::version($request);
    // }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $user = Auth::user();

        $locale = app()->getLocale();
        $defaultLocale = config('app.fallback_locale');

        return [
            ...parent::share($request),
            //
            'locales' => config('app.locales'),
            'locale' => $locale,
            'defaultLocale' => $defaultLocale,
            'translations' => $this->getTranslations($locale ?? $defaultLocale),
            'mottoOfTheDay' => app(RandomMottoService::class)->pick(),
            'auth' => [
                'user' => $user ? UserData::from(UserItemMapper::toDomain($user)) : null,
                'capabilities' => app(AbilityResolver::class)->forUser($user),
            ],
            'flash' => [
                'success' => fn () => $request->hasSession() ? $request->session()->get('success') : null,
                'error' => fn () => $request->hasSession() ? $request->session()->get('error') : null,
            ],
        ];
    }

    /**
     * Carica tutte le traduzioni per la locale corrente
     */
    private function getTranslations(string $locale): array
    {
        $langPath = lang_path($locale);

        if (!is_dir($langPath)) {
            return [];
        }

        $translations = [];

        foreach (glob("$langPath/*.php") as $file) {
            $key = basename($file, '.php');
            $translations[$key] = require $file;
        }

        return $translations;
    }
}
