<?php

namespace Tests\Feature\Performance;

use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PerformanceQueryCountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function listar_proyectos_no_ejecuta_mas_de_cinco_consultas()
    {
        $user = User::factory()->create();
        Proyecto::factory()->count(5)->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        DB::enableQueryLog();
        $this->getJson('/api/proyectos')->assertStatus(200);
        $this->assertLessThanOrEqual(5, count(DB::getQueryLog()));
    }
}
