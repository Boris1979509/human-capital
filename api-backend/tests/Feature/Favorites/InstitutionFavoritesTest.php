<?php

namespace Tests\Feature\Favorites;

use App\Models\Institution\Institution;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstitutionFavoritesTest extends TestCase
{
    use RefreshDatabase;

    private Institution $institution;

    protected function setUp(): void
    {
        parent::setUp();
        $this->institution = $this->createInstitution();
    }

    /** @test */
    public function only_authorized_user_can_add_institution_to_favorites(): void
    {
        $this->json('post', route('favorites.create', ['institution', $this->institution]))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_adds_institution_to_favorites(): void
    {
        $favoritedUser = $this->createUser();

        $this->assertFalse($this->institution->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'post',
            route('favorites.create', ['institution', $this->institution])
        );
        $response->assertCreated();

        $this->assertTrue($this->institution->isFavorited($favoritedUser->id));
    }


    /** @test */
    public function adding_to_favorite_is_idempotent(): void
    {
        $favoritedUser = $this->createUser();
        $this->institution->addFavorite($favoritedUser->id);

        $this->assertTrue($this->institution->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'post',
            route('favorites.create', ['institution', $this->institution])
        );
        $response->assertCreated();

        $this->assertTrue($this->institution->isFavorited($favoritedUser->id));
    }

    /** @test */
    public function only_authorized_user_can_remove_institution_from_favorites(): void
    {
        $this->json('delete', route('favorites.delete', ['institution', $this->institution]))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_removes_institution_from_favorites(): void
    {
        $favoritedUser = $this->createUser();
        $this->institution->addFavorite($favoritedUser->id);

        $this->assertTrue($this->institution->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'delete',
            route('favorites.delete', ['institution', $this->institution])
        );
        $response->assertNoContent();

        $this->assertFalse($this->institution->isFavorited($favoritedUser->id));
    }


    /** @test */
    public function removing_from_favorite_is_idempotent(): void
    {
        $favoritedUser = $this->createUser();

        $this->assertFalse($this->institution->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'delete',
            route('favorites.delete', ['institution', $this->institution])
        );
        $response->assertNoContent();

        $this->assertFalse($this->institution->isFavorited($favoritedUser->id));
    }
}
