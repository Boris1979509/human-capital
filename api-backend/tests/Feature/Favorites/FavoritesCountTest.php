<?php

namespace Tests\Feature\Favorites;

use App\Models\Institution\InstitutionCurriculum;
use ChristianKuri\LaravelFavorite\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoritesCountTest extends TestCase
{
    use RefreshDatabase;

    private InstitutionCurriculum $curriculum;


    /** @test */
    public function only_authorized_user_can_fetch_his_favorites_count(): void
    {
        $this->json('get', route('favorites.count'))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_fetches_favorites_count(): void
    {
        $max = $this->createUser();
        $taylor = $this->createUser();

        $institution = $this->createInstitution();
        $institution->addFavorite($max->id);
        $institution->addFavorite($taylor->id);

        $curriculum = $this->createCurriculaForInstitution($institution)->first();
        $curriculum->addFavorite($max->id);
        $curriculum->addFavorite($taylor->id);

        $content = $this->createContent();
        $content->addFavorite($max->id);
        $content->addFavorite($taylor->id);

        $selection = $this->createSelection();
        $selection->addFavorite($max->id);
        $selection->addFavorite($taylor->id);

        $this->assertEquals(8, Favorite::count());

        $response = $this->actingAs($max)->json('get', route('favorites.count'));
        $response->assertOk();

        $this->assertEquals(4, $response->json());
    }
}
