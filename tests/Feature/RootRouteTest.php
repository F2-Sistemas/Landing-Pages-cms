<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RootRouteTest extends TestCase
{
    /**
     * @test
     */
    public function validaAcessoViaCaminhoPrincipal(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
