<?php

namespace Tests\Feature\Selection;

use App\Http\Resources\Selection\SelectionResource;
use App\Models\Selection\Selection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SelectionShowTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";

    protected function setUp(): void
    {
        parent::setUp();
        $selection = Selection::factory()->create();
        $this->endpoint = route('selections.show', $selection->id);
    }

    /** @test */
    public function it_calculates_selection_read_time(): void
    {
        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();
        $this->assertEquals('1 мин.', $response->json('data.reading_time'));
    }
}
