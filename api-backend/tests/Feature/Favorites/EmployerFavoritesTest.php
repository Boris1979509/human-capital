<?php

namespace Tests\Feature\Favorites;

use App\Models\Employer\Employer;
use ChristianKuri\LaravelFavorite\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployerFavoritesTest extends TestCase
{
    use RefreshDatabase;

    private Employer $employer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->employer = $this->createEmployer();
    }

    /** @test */
    public function only_authorized_user_can_add_employer_to_favorites(): void
    {
        $this->json('post', route('favorites.create', ['employer', $this->employer]))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_adds_employer_to_favorites(): void
    {
        $favoritedUser = $this->createUser();

        $this->assertFalse($this->employer->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'post',
            route('favorites.create', ['employer', $this->employer])
        );
        $response->assertCreated();

        $this->assertTrue($this->employer->isFavorited($favoritedUser->id));
    }


    /** @test */
    public function adding_to_favorite_is_idempotent(): void
    {
        $favoritedUser = $this->createUser();
        $this->employer->addFavorite($favoritedUser->id);

        $this->assertTrue($this->employer->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'post',
            route('favorites.create', ['employer', $this->employer])
        );
        $response->assertCreated();

        $this->assertTrue($this->employer->isFavorited($favoritedUser->id));
    }

    /** @test */
    public function only_authorized_user_can_remove_employer_from_favorites(): void
    {
        $this->json('delete', route('favorites.delete', ['employer', $this->employer]))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_removes_employer_from_favorites(): void
    {
        $favoritedUser = $this->createUser();
        $this->employer->addFavorite($favoritedUser->id);

        $this->assertTrue($this->employer->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'delete',
            route('favorites.delete', ['employer', $this->employer])
        );
        $response->assertNoContent();

        $this->assertFalse($this->employer->isFavorited($favoritedUser->id));
    }


    /** @test */
    public function removing_from_favorite_is_idempotent(): void
    {
        $favoritedUser = $this->createUser();

        $this->assertFalse($this->employer->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'delete',
            route('favorites.delete', ['employer', $this->employer])
        );
        $response->assertNoContent();

        $this->assertFalse($this->employer->isFavorited($favoritedUser->id));
    }
}
