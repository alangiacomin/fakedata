<?php

namespace App\Cqrs\App\Presentation\Http\Controllers;

use App\Cqrs\App\Application\Commands\SyncCommand;
use App\Cqrs\Infrastructure\Bus\MessageBus;
use App\Cqrs\Infrastructure\Routing\LocalizedRouteGenerator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    use AuthorizesRequests;

    public function execute(SyncCommand $command): mixed
    {
        return $this->bus()->dispatch($command);
    }

    protected function bus(): MessageBus
    {
        return app(MessageBus::class);
    }

    public function flashSuccess(mixed $returnValue): RedirectResponse
    {
        return back()->with('success', $returnValue);
    }

    public function spaRedirect(string $route): RedirectResponse
    {
        return redirect()->intended($route);
    }

    public function hardRedirect(string $route): Response
    {
        return Inertia::location(redirect()->intended($route)->getTargetUrl());
    }

    protected function routeGenerator(): LocalizedRouteGenerator
    {
        return app(LocalizedRouteGenerator::class);
    }
}
