<?php

namespace App\Repositories\Contracts;

use App\Models\Propuesta;

interface PropuestaRepositoryInterface
{
    public function obtenerTodas();
    public function crear(array $data): Propuesta;
    public function obtenerPorId(int $id): ?Propuesta;
    public function actualizar(int $id, array $data): Propuesta;
    public function eliminar(int $id): void;
}
