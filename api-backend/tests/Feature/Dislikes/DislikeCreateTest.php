<?php

namespace Tests\Feature\Dislikes;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DislikeCreateTest extends TestCase
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
    public function only_authorized_user_can_dislike_comment(): void
    {
        $this->json('post', route('dislikes.create', ['comment', $this->comment->id]))
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_dislike_comment(): void
    {
        $user = $this->createUser();

        $this->assertEquals(0, $this->comment->dislikes()->count());

        $response = $this->actingAs($user)->json(
            'post',
            route('dislikes.create', ['comment', $this->comment->id]),
        );
        $response->assertCreated();

        $this->assertEquals(1, $this->comment->dislikes()->count());
        $this->assertDatabaseHas('dislikes', [
            'dislikeable_type' => 'comment',
            'dislikeable_id' => $this->comment->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function create_dislike_endpoint_is_idempotent(): void
    {
        $this->withoutExceptionHandling();
        $user = $this->createUser();

        $this->comment->dislikes()->create(['user_id' => $user->id]);

        $this->assertEquals(1, $this->comment->dislikes()->count());

        $response = $this->actingAs($user)->json(
            'post',
            route('dislikes.create', ['comment', $this->comment->id]),
        );
        $response->assertCreated();

        $this->assertEquals(1, $this->comment->dislikes()->count());
    }

    /** @test */
    public function it_responds_with_404_when_unknown_dislikeable_id_or_dislikeable_type_is_provided(): void
    {
        $user = $this->createUser();
        $this->actingAs($user)
            ->json('post', route('dislikes.create', ['unknown', $this->comment->id]))
            ->assertNotFound();

        $unknownCommentId = 999;
        $this->assertDatabaseMissing('comments', ['id' => $unknownCommentId]);
        $this->actingAs($user)
            ->json('post', route('dislikes.create', ['comment', $unknownCommentId]))
            ->assertNotFound();
    }
}
