<?php

namespace Tests\Feature\Region;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegionIndexTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";

    protected function setUp(): void
    {
        parent::setUp();
        $this->endpoint = route('region.index');
    }

    /** @test */
    public function it_fetches_region_from_config(): void
    {
        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();

        $data = $response->json();
        $this->assertArrayHasKey('name', $data);
        $this->assertEquals(config('app.region'), $data['name']);
    }
}
