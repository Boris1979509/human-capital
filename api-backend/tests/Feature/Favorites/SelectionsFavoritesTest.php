<?php

namespace Tests\Feature\Favorites;

use App\Models\Selection\Selection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SelectionsFavoritesTest extends TestCase
{
    use RefreshDatabase;

    private Selection $selection;

    protected function setUp(): void
    {
        parent::setUp();
        $this->selection = $this->createSelection();
    }

    /** @test */
    public function only_authorized_user_can_add_institution_to_favorites(): void
    {
        $this->json('post', route('favorites.create', ['selection', $this->selection]))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_adds_institution_to_favorites(): void
    {
        $this->withoutExceptionHandling();
        $favoritedUser = $this->createUser();

        $this->assertFalse($this->selection->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'post',
            route('favorites.create', ['selection', $this->selection])
        );
        $response->assertCreated();

        $this->assertTrue($this->selection->isFavorited($favoritedUser->id));
    }


    /** @test */
    public function adding_to_favorite_is_idempotent(): void
    {
        $favoritedUser = $this->createUser();
        $this->selection->addFavorite($favoritedUser->id);

        $this->assertTrue($this->selection->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'post',
            route('favorites.create', ['selection', $this->selection])
        );
        $response->assertCreated();

        $this->assertTrue($this->selection->isFavorited($favoritedUser->id));
    }

    /** @test */
    public function only_authorized_user_can_remove_institution_from_favorites(): void
    {
        $this->json('delete', route('favorites.delete', ['selection', $this->selection]))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_removes_institution_from_favorites(): void
    {
        $favoritedUser = $this->createUser();
        $this->selection->addFavorite($favoritedUser->id);

        $this->assertTrue($this->selection->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'delete',
            route('favorites.delete', ['selection', $this->selection])
        );
        $response->assertNoContent();

        $this->assertFalse($this->selection->isFavorited($favoritedUser->id));
    }


    /** @test */
    public function removing_from_favorite_is_idempotent(): void
    {
        $favoritedUser = $this->createUser();

        $this->assertFalse($this->selection->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'delete',
            route('favorites.delete', ['selection', $this->selection])
        );
        $response->assertNoContent();

        $this->assertFalse($this->selection->isFavorited($favoritedUser->id));
    }
}
