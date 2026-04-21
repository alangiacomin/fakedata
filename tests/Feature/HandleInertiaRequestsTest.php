<?php

use App\Areas\Main\Motto\Application\Services\RandomMottoService;
use Inertia\Testing\AssertableInertia;

afterEach(function () {
    Mockery::close();
});

it('condivide il motto del giorno nelle props shared di inertia', function () {
    config()->set('session.driver', 'array');
    config()->set('inertia.testing.ensure_pages_exist', false);

    $service = Mockery::mock(RandomMottoService::class);
    $service->shouldReceive('pick')->once()->andReturn('Motto di test.');

    $this->app->instance(RandomMottoService::class, $service);

    $this->get('/')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('App/Home/Home')
            ->where('mottoOfTheDay', 'Motto di test.')
        );
});
