<?php

namespace Tests\Feature\Likes;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeDeleteTest extends TestCase
{
    use RefreshDatabase;

    private Comment $comment;

    protected function setUp(): void
    {
        parent::setUp();
        $this->comment = $this->createComment([
            'commentable_id' => 1,
            'commentable_type' => 'journal',
            'user_id' => $this->createUser()->id
        ]);
    }

    /** @test */
    public function only_authorized_user_can_remove_like(): void
    {
        $this->json('delete', route('likes.delete', ['comment', $this->comment->id]))
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_delete_his_like(): void
    {
        $user = $this->createUser();

        $this->comment->likes()->create(['user_id' => $user->id]);

        $this->assertEquals(1, $this->comment->likes()->count());

        $response = $this->actingAs($user)->json(
            'delete',
            route('likes.delete', ['comment', $this->comment->id]),
        );
        $response->assertNoContent();

        $this->assertEquals(0, $this->comment->likes()->count());
    }

    /** @test */
    public function delete_like_endpoint_is_idempotent(): void
    {
        $user = $this->createUser();

        $this->assertEquals(0, $this->comment->likes()->count());

        $response = $this->actingAs($user)->json(
            'delete',
            route('likes.delete', ['comment', $this->comment->id]),
        );
        $response->assertNoContent();

        $this->assertEquals(0, $this->comment->likes()->count());
    }

    /** @test */
    public function it_responds_with_404_when_unknown_likeable_id_or_likeable_type_is_provided(): void
    {
        $user = $this->createUser();
        $this->actingAs($user)
            ->json('delete', route('likes.delete', ['unknown', $this->comment->id]))
            ->assertNotFound();

        $unknownCommentId = 999;
        $this->assertDatabaseMissing('comments', ['id' => $unknownCommentId]);
        $this->actingAs($user)
            ->json('delete', route('likes.delete', ['comment', $unknownCommentId]))
            ->assertNotFound();
    }
}
