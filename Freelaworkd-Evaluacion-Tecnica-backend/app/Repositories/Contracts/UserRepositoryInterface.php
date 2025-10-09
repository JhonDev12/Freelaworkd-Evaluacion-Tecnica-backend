<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
 
    public function obtenerTodos();

 
    public function crear(array $data): User;

   
    public function obtenerPorId(int $id): ?User;

  
    public function actualizar(int $id, array $data): User;

    public function eliminar(int $id): void;
}
