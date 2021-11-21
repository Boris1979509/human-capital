<?php

namespace Tests\Feature\Favorites;

use App\Models\Institution\InstitutionCurriculum;
use ChristianKuri\LaravelFavorite\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurriculaFavoritesTest extends TestCase
{
    use RefreshDatabase;

    private InstitutionCurriculum $curriculum;

    protected function setUp(): void
    {
        parent::setUp();
        $this->curriculum = $this->createCurriculaForInstitution($this->createInstitution())->first();
    }

    /** @test */
    public function only_authorized_user_can_add_institution_to_favorites(): void
    {
        $this->json('post', route('favorites.create', ['curriculum', $this->curriculum]))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_adds_institution_to_favorites(): void
    {
        $this->withoutExceptionHandling();
        $favoritedUser = $this->createUser();

        $this->assertFalse($this->curriculum->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'post',
            route('favorites.create', ['curriculum', $this->curriculum])
        );
        $response->assertCreated();

        $this->assertTrue($this->curriculum->isFavorited($favoritedUser->id));
    }


    /** @test */
    public function adding_to_favorite_is_idempotent(): void
    {
        $favoritedUser = $this->createUser();
        $this->curriculum->addFavorite($favoritedUser->id);

        $this->assertTrue($this->curriculum->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'post',
            route('favorites.create', ['curriculum', $this->curriculum])
        );
        $response->assertCreated();

        $this->assertTrue($this->curriculum->isFavorited($favoritedUser->id));
    }

    /** @test */
    public function only_authorized_user_can_remove_institution_from_favorites(): void
    {
        $this->json('delete', route('favorites.delete', ['curriculum', $this->curriculum]))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_removes_institution_from_favorites(): void
    {
        $favoritedUser = $this->createUser();
        $this->curriculum->addFavorite($favoritedUser->id);

        $this->assertTrue($this->curriculum->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'delete',
            route('favorites.delete', ['curriculum', $this->curriculum])
        );
        $response->assertNoContent();

        $this->assertFalse($this->curriculum->isFavorited($favoritedUser->id));
    }


    /** @test */
    public function removing_from_favorite_is_idempotent(): void
    {
        $favoritedUser = $this->createUser();

        $this->assertFalse($this->curriculum->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'delete',
            route('favorites.delete', ['curriculum', $this->curriculum])
        );
        $response->assertNoContent();

        $this->assertFalse($this->curriculum->isFavorited($favoritedUser->id));
    }
}
