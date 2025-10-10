<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\AuthRegisterRequest;
use Tests\TestCase;

class AuthRegisterRequestTest extends TestCase
{
    /** @test */
    public function valida_las_reglas_de_registro()
    {
        $request = new AuthRegisterRequest;

        $this->assertEquals([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], $request->rules());
    }

    /** @test */
    public function autoriza_la_solicitud_por_defecto()
    {
        $request = new AuthRegisterRequest;

        $this->assertTrue($request->authorize());
    }
}
