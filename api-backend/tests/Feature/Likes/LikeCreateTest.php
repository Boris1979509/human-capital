<?php

namespace Tests\Feature\Likes;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeCreateTest extends TestCase
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
    public function only_authorized_user_can_like_comment(): void
    {
        $this->json('post', route('likes.create', ['comment', $this->comment->id]))
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_like_comment(): void
    {
        $user = $this->createUser();

        $this->assertEquals(0, $this->comment->likes()->count());

        $response = $this->actingAs($user)->json(
            'post',
            route('likes.create', ['comment', $this->comment->id]),
        );
        $response->assertCreated();

        $this->assertEquals(1, $this->comment->likes()->count());
        $this->assertDatabaseHas('likes', [
            'likeable_type' => 'comment',
            'likeable_id' => $this->comment->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function create_like_endpoint_is_idempotent(): void
    {
        $this->withoutExceptionHandling();
        $user = $this->createUser();

        $this->comment->likes()->create(['user_id' => $user->id]);

        $this->assertEquals(1, $this->comment->likes()->count());

        $response = $this->actingAs($user)->json(
            'post',
            route('likes.create', ['comment', $this->comment->id]),
        );
        $response->assertCreated();

        $this->assertEquals(1, $this->comment->likes()->count());
    }

    /** @test */
    public function it_responds_with_404_when_unknown_likeable_id_or_likeable_type_is_provided(): void
    {
        $user = $this->createUser();
        $this->actingAs($user)
            ->json('post', route('likes.create', ['unknown', $this->comment->id]))
            ->assertNotFound();

        $unknownCommentId = 999;
        $this->assertDatabaseMissing('comments', ['id' => $unknownCommentId]);
        $this->actingAs($user)
            ->json('post', route('likes.create', ['comment', $unknownCommentId]))
            ->assertNotFound();
    }
}
