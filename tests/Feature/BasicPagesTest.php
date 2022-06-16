<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BasicPagesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_redirect_response()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
    }

    public function test_the_login_returns_a_successful_response()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
}
