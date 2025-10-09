<?php

namespace App\Repositories\Contracts;

interface ProyectoRepositoryInterface
{
    public function obtenerTodos();
    public function crear(array $datos);
    public function obtenerPorId(int $id);
    public function actualizar(int $id, array $datos);
    public function eliminar(int $id): void;
}
