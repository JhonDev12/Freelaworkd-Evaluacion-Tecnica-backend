<?php

namespace App\Services;

use App\Models\Habilidad;
use App\Repositories\Contracts\HabilidadRepositoryInterface;

/**
 * Servicio del dominio Habilidad.
 *
 * Centraliza la lógica de negocio relacionada con las habilidades
 * y delega las operaciones de persistencia al repositorio correspondiente.
 *
 * Mantiene el principio de separación de responsabilidades (SRP),
 * actuando como intermediario entre los controladores y la capa de datos.
 */
class HabilidadService
{
    public function __construct(private HabilidadRepositoryInterface $habilidadRepository) {}

    public function listar()
    {
        return $this->habilidadRepository->obtenerTodas();
    }

    public function crear(array $data): Habilidad
    {
        return $this->habilidadRepository->crear($data);
    }

    public function obtenerPorId(int $id): Habilidad
    {
        return $this->habilidadRepository->obtenerPorId($id);
    }

    public function actualizar(int $id, array $data): Habilidad
    {
        return $this->habilidadRepository->actualizar($id, $data);
    }

    public function eliminar(int $id): void
    {
        $this->habilidadRepository->eliminar($id);
    }
}
