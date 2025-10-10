<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\AuthLoginRequest;
use Tests\TestCase;

class AuthLoginRequestTest extends TestCase
{
    /** @test */
    public function valida_las_reglas_de_login()
    {
        $request = new AuthLoginRequest;

        $this->assertEquals([
            'email'    => 'required|email|max:150',
            'password' => 'required|string|min:8|max:64',
        ], $request->rules());
    }

    /** @test */
    public function autoriza_la_solicitud_por_defecto()
    {
        $request = new AuthLoginRequest;

        $this->assertTrue($request->authorize());
    }
}
