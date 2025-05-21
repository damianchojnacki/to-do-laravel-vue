<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_status_page_returns_a_correct_response(): void
    {
        $this->get('/api')
            ->assertOk()
            ->assertJson(['status' => 'ok']);
    }
}
