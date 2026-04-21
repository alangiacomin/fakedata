<?php

namespace App\Areas\Main\Persona\Presentation\Http\Controllers;

use App\Cqrs\App\Presentation\Http\Controllers\Controller;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Inertia\Response;
use Inertia\ResponseFactory;

class FallbackController extends Controller
{
    public function __construct(
    ) {}

    #[Middleware('not_banned')]
    public function app(): Response|ResponseFactory
    {
        return inertia('App/Home/Home');
    }

    public function notFound(): Response|ResponseFactory
    {
        abort(404);
    }
}
