<?php

namespace Tests\Feature\Comments;

use App\Models\Comment;
use App\Models\Journal\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JournalCommentsIndexTest extends TestCase
{
    use RefreshDatabase;

    private Content $content;
    private string $method = 'get';

    protected function setUp(): void
    {
        parent::setUp();
        $this->content = $this->createContent();
    }

    /** @test */
    public function user_can_fetch_content_comments(): void
    {
        $this->withoutExceptionHandling();
        $user = $this->createUser();

        $rootComment1 = Comment::factory()->create([
            'commentable_id' => $this->content->id,
            'commentable_type' => 'journal',
            'parent_id' => null,
            'user_id' => $user->id,
            'body' => 'body'
        ]);
        $rootComment2 = Comment::factory()->create([
            'commentable_id' => $this->content->id,
            'commentable_type' => 'journal',
            'parent_id' => null,
            'user_id' => $user->id,
            'body' => 'body'
        ]);
        $secondLevelComment1 = Comment::factory()->create([
            'commentable_id' => $this->content->id,
            'commentable_type' => 'journal',
            'parent_id' => $rootComment1->id,
            'user_id' => $user->id,
            'body' => 'body'
        ]);
        $secondLevelComment2 = Comment::factory()->create([
            'commentable_id' => $this->content->id,
            'commentable_type' => 'journal',
            'parent_id' => $rootComment1->id,
            'user_id' => $user->id,
            'body' => 'body'
        ]);
        $thirdLevelComment = Comment::factory()->create([
            'commentable_id' => $this->content->id,
            'commentable_type' => 'journal',
            'parent_id' => $secondLevelComment1->id,
            'user_id' => $user->id,
            'body' => 'body'
        ]);

        Comment::factory()->create([
            'commentable_id' => $this->createContent()->id,
            'commentable_type' => 'journal',
            'parent_id' => null,
            'user_id' => $user->id,
            'body' => 'body'
        ]);

        $this->assertEquals(5, $this->content->comments()->count());
        $this->assertEquals(6, Comment::count());

        $response = $this->json(
            $this->method,
            route('comments.index', ['journal', $this->content->id])
        );
        $response->assertOk();

        $fetchedComments = $response->json('data');

        // первый уровень вложенности
        $this->assertCount(2, $fetchedComments);
        $this->assertEquals($rootComment1->id, $fetchedComments[1]['id']);
        $this->assertEquals($rootComment2->id, $fetchedComments[0]['id']);

        // второй уровень вложенности
        $rootComment1Replies = $fetchedComments[1]['replies'];
        $this->assertCount(2, $rootComment1Replies);
        $this->assertEquals($secondLevelComment2->id, $rootComment1Replies[0]['id']);
        $this->assertEquals($secondLevelComment1->id, $rootComment1Replies[1]['id']);

        // третий уровень вложенности
        $secondLevelComment2Replies = $rootComment1Replies[1]['replies'];
        $this->assertCount(1, $secondLevelComment2Replies);
        $this->assertEquals($thirdLevelComment->id, $secondLevelComment2Replies[0]['id']);
    }

    /** @test */
    public function it_responds_with_404_when_unknown_commentable_id_or_commentable_type_is_provided(): void
    {
        $user = $this->createUser();
        $this->actingAs($user)
            ->json($this->method, route('comments.index', ['unknown', $this->content->id]), ['body' => 'body'])
            ->assertNotFound();

        $unknownJournalId = 999;
        $this->assertDatabaseMissing('contents', ['id' => $unknownJournalId]);
        $this->actingAs($user)
            ->json($this->method, route('comments.index', ['journal', $unknownJournalId]), ['body' => 'body'])
            ->assertNotFound();
    }
}
