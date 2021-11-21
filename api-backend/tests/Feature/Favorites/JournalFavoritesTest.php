<?php

namespace Tests\Feature\Favorites;

use App\Models\Journal\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JournalFavoritesTest extends TestCase
{
    use RefreshDatabase;

    private Content $content;

    protected function setUp(): void
    {
        parent::setUp();
        $this->content = $this->createContent();
    }

    /** @test */
    public function only_authorized_user_can_add_content_to_favorites(): void
    {
        $this->json('post', route('favorites.create', ['journal', $this->content]))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_adds_content_to_favorites(): void
    {
        $favoritedUser = $this->createUser();

        $this->assertFalse($this->content->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'post',
            route('favorites.create', ['journal', $this->content])
        );
        $response->assertCreated();

        $this->assertTrue($this->content->isFavorited($favoritedUser->id));
    }


    /** @test */
    public function adding_to_favorite_is_idempotent(): void
    {
        $favoritedUser = $this->createUser();
        $this->content->addFavorite($favoritedUser->id);

        $this->assertTrue($this->content->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'post',
            route('favorites.create', ['journal', $this->content])
        );
        $response->assertCreated();

        $this->assertTrue($this->content->isFavorited($favoritedUser->id));
    }

    /** @test */
    public function only_authorized_user_can_remove_content_from_favorites(): void
    {
        $this->json('delete', route('favorites.delete', ['journal', $this->content]))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_removes_content_from_favorites(): void
    {
        $favoritedUser = $this->createUser();
        $this->content->addFavorite($favoritedUser->id);

        $this->assertTrue($this->content->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'delete',
            route('favorites.delete', ['journal', $this->content])
        );
        $response->assertNoContent();

        $this->assertFalse($this->content->isFavorited($favoritedUser->id));
    }


    /** @test */
    public function removing_from_favorite_is_idempotent(): void
    {
        $favoritedUser = $this->createUser();

        $this->assertFalse($this->content->isFavorited($favoritedUser->id));

        $response = $this->actingAs($favoritedUser)->json(
            'delete',
            route('favorites.delete', ['journal', $this->content])
        );
        $response->assertNoContent();

        $this->assertFalse($this->content->isFavorited($favoritedUser->id));
    }
}
