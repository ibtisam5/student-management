<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_redirects_to_students_page(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('students.index'));
    }

    public function test_students_page_returns_a_successful_response(): void
    {
        $response = $this->get('/students');

        $response->assertStatus(200);
    }
}
