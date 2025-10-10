<?php

namespace Tests\Unit;

use App\Models\Habilidad;
use Tests\TestCase;

class HabilidadTest extends TestCase
{
    /** @test */
    public function puede_crear_una_habilidad()
    {
        $habilidad = Habilidad::factory()->create([
            'nombre' => 'Laravel',
            'nivel'  => 'avanzado',
        ]);

        $this->assertDatabaseHas('habilidades', [
            'nombre' => 'Laravel',
        ]);
    }
}
