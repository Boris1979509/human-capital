<?php

namespace Tests\Feature\Selection;

use App\Http\Resources\Selection\SelectionResource;
use App\Models\Selection\Selection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SelectionsIndexTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";

    protected function setUp(): void
    {
        parent::setUp();
        $this->endpoint = route('selections.index');
    }

    /** @test */
    public function it_fetches_selections(): void
    {
        $selections = Selection::factory()->count(3)->create();
        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();
        $this->assertCount(3, $response->json('data'));
        $this->assertEquals(
            SelectionResource::collection($selections->fresh())->response()->getData(true)['data'],
            $response->json('data')
        );
    }

    /** @test */
    public function it_filters_selections_by_favorite(): void
    {
        $user = $this->createUser();
        $favoritedSelection = $this->createSelection();
        $user->addFavorite($favoritedSelection);

        $notFavoritedSelection = $this->createSelection();
        $this->createUser()->addFavorite($notFavoritedSelection);

        $this->assertDatabaseCount('selections', 2);

        $response = $this->json($this->method, $this->endpoint, ['favorited' => true]);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));

        $response = $this->actingAs($user)->json($this->method, $this->endpoint, ['favorited' => true]);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($favoritedSelection->id, $response->json('data.0.id'));
    }
}
