<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_visitors_enter_the_application_through_login(): void
    {
        $this->get('/')->assertRedirect('/login');
    }
}
