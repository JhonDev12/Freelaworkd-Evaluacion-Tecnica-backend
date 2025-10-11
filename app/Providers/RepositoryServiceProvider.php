<?php

namespace App\Providers;

use App\Repositories\Contracts\HabilidadRepositoryInterface;
use App\Repositories\Contracts\PropuestaRepositoryInterface;
use App\Repositories\Contracts\ProyectoRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\HabilidadRepository;
use App\Repositories\PropuestaRepository;
use App\Repositories\ProyectoRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

/**
 * RepositoryServiceProvider
 *
 * Registra las dependencias entre las interfaces de repositorio
 * y sus implementaciones concretas. Aplica el principio de
 * Inversión de Dependencias (SOLID - D), garantizando que los
 * servicios trabajen sobre abstracciones en lugar de clases concretas.
 *
 * Beneficios:
 * - Código desacoplado y fácil de mantener.
 * - Facilita el testing (inyección y mocking).
 * - Mejora la escalabilidad del sistema.
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Registra los bindings entre interfaces e implementaciones.
     */
    public function register(): void
    {
        $this->app->bind(ProyectoRepositoryInterface::class, ProyectoRepository::class);
        $this->app->bind(PropuestaRepositoryInterface::class, PropuestaRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepositoryInterface::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(HabilidadRepositoryInterface::class, HabilidadRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class
        );

    }

    /**
     * Inicialización de servicios adicionales (no requerido en este caso).
     */
    public function boot(): void
    {
        //
    }
}
