<?php

use App\Cqrs\LaravelCqrsServiceProvider;
use App\Infrastructure\Providers\AppServiceProvider;
use App\Infrastructure\Providers\EventServiceProvider;

return [
    LaravelCqrsServiceProvider::class,
    AppServiceProvider::class,
    EventServiceProvider::class,
];
