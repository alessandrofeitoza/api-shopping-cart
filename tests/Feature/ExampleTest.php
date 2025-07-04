<?php

declare(strict_types=1);

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function testTheApplicationReturnsASuccessfulResponse(): void
    {
        $response = $this->get('/api/test');

        $response->assertStatus(200);
    }
}
