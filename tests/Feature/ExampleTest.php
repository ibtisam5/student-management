<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_redirects_to_dashboard(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('dashboard'));
    }

    public function test_students_page_returns_a_successful_response(): void
    {
        $response = $this->get('/students');

        $response->assertStatus(200);
    }

    public function test_dashboard_page_returns_a_successful_response(): void
    {
        $response = $this->get('/dashboard');

        $response->assertStatus(200);
    }
}
