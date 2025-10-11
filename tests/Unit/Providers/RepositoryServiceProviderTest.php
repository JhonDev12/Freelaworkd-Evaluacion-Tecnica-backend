<?php

namespace Tests\Unit\Providers;

use App\Providers\RepositoryServiceProvider;
use App\Repositories\Contracts\ProyectoRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Tests\TestCase;

class RepositoryServiceProviderTest extends TestCase
{
    /** @test */
    public function se_registran_los_repositorios_correctamente()
    {
        $app      = $this->app;
        $provider = new RepositoryServiceProvider($app);
        $provider->register();

        $this->assertTrue($app->bound(UserRepositoryInterface::class));
        $this->assertTrue($app->bound(ProyectoRepositoryInterface::class));
    }
}
