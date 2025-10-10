<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ejecutar migraciones automáticamente en cada test
        $this->artisan('migrate', ['--env' => 'testing']);
    }
}
