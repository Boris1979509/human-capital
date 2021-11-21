<?php

namespace Tests\Feature\Institution;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstitutionPublicIndexTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";

    protected function setUp(): void
    {
        parent::setUp();
        $this->endpoint = route('institutions.public.index');
    }

    /** @test */
    public function it_filters_institutions_by_favorite(): void
    {
        $user = $this->createUser();
        $favoritedPublicInstitution = $this->createInstitution();
        $user->addFavorite($favoritedPublicInstitution);

        $notFavoritedInstitution = $this->createInstitution();
        $this->createUser()->addFavorite($notFavoritedInstitution);

        $this->assertDatabaseCount('institutions', 2);

        $response = $this->json($this->method, $this->endpoint, ['favorited' => true]);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));

        $response = $this->actingAs($user)->json($this->method, $this->endpoint, ['favorited' => true]);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($favoritedPublicInstitution->id, $response->json('data.0.id'));
    }

    /** @test */
    public function it_filters_institutions_by_types(): void
    {
        $red = $this->createDictionary();
        $green = $this->createDictionary();
        $blue = $this->createDictionary();

        $redInstitution = $this->createInstitution(['inst_type_id' => $red->id]);
        $greenInstitution = $this->createInstitution(['inst_type_id' => $green->id]);
        $blueInstitution = $this->createInstitution(['inst_type_id' => $blue->id]);

        $this->assertDatabaseCount('institutions', 3);

        $response = $this->json($this->method, $this->endpoint, ['type' => [$red->id, $green->id]]);
        $response->assertOk();

        $this->assertCount(2, $response->json('data'));
        $fetchedIds = collect($response->json('data'))->pluck('id');
        $this->assertContains($redInstitution->id, $fetchedIds);
        $this->assertContains($greenInstitution->id, $fetchedIds);
        $this->assertNotContains($blueInstitution->id, $fetchedIds);
    }
}
