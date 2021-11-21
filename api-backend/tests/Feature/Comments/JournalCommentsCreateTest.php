<?php

namespace Tests\Feature\Comments;

use App\Models\Comment;
use App\Models\Journal\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JournalCommentsCreateTest extends TestCase
{
    use RefreshDatabase;

    private Content $content;

    protected function setUp(): void
    {
        parent::setUp();
        $this->content = $this->createContent();
    }

    /** @test */
    public function only_authorized_user_can_comment(): void
    {
        $this->json('post', route('comments.create', ['journal', $this->content->id]))
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_comment_on_content(): void
    {
        $user = $this->createUser();

        $this->assertEquals(0, $this->content->comments()->count());

        $response = $this->actingAs($user)->json(
            'post',
            route('comments.create', ['journal', $this->content->id]),
            ['body' => 'body']
        );
        $response->assertCreated();

        $this->assertEquals(1, $this->content->comments()->count());
        $this->assertDatabaseHas('comments', [
            'commentable_type' => 'journal',
            'commentable_id' => $this->content->id,
            'approved' => true,
            'user_id' => $user->id,
            'body' => 'body'
        ]);
    }

    /** @test */
    public function user_can_reply_to_another_comment(): void
    {
        $user = $this->createUser();
        $comment = $this->content->comments()->create(['body' => 'body', 'user_id' => $this->createUser()->id]);
        $this->assertEquals(1, $this->content->comments()->count());

        $response = $this->actingAs($user)->json(
            'post',
            route('comments.create', ['journal', $this->content->id]),
            [
                'body' => 'reply',
                'parent_id' => $comment->id
            ]
        );
        $response->assertCreated();

        $this->assertEquals(2, $this->content->comments()->count());
        $this->assertDatabaseHas('comments', [
            'commentable_type' => 'journal',
            'commentable_id' => $this->content->id,
            'approved' => true,
            'body' => 'reply',
            'user_id' => $user->id,
            'parent_id' => $comment->id
        ]);
    }

    /** @test */
    public function it_responds_with_404_when_unknown_commentable_id_or_commentable_type_is_provided(): void
    {
        $user = $this->createUser();
        $this->actingAs($user)
            ->json('post', route('comments.create', ['unknown', $this->content->id]), ['body' => 'body'])
            ->assertNotFound();

        $unknownJournalId = 999;
        $this->assertDatabaseMissing('contents', ['id' => $unknownJournalId]);
        $this->actingAs($user)
            ->json('post', route('comments.create', ['journal', $unknownJournalId]), ['body' => 'body'])
            ->assertNotFound();
    }
}
