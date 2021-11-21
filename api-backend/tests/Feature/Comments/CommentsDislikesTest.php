<?php

namespace Tests\Feature\Comments;

use App\Models\Dislike;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentsDislikesTest extends TestCase
{
    use RefreshDatabase;

    private string $method = 'get';

    /** @test */
    public function user_can_fetch_content_comments(): void
    {
        $user = $this->createUser();
        $content = $this->createContent();
        $comment = $this->createComment([
            'commentable_id' => $content->id,
            'commentable_type' => 'journal',
            'parent_id' => null,
            'user_id' => $user->id,
            'body' => 'body'
        ]);
        Dislike::create([
            'dislikeable_type' => 'comment',
            'dislikeable_id' => $comment->id,
            'user_id' => $user->id
        ]);
        Dislike::create([
            'dislikeable_type' => 'comment',
            'dislikeable_id' => $comment->id,
            'user_id' => $this->createUser()->id
        ]);

        $response = $this->json($this->method, route('comments.index', ['journal', $content->id]));
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertArrayHasKey('dislikes_count', $response->json('data.0'));
        $this->assertEquals(2, $response->json('data.0.dislikes_count'));
        $this->assertArrayHasKey('is_disliked', $response->json('data.0'));
        $this->assertFalse($response->json('data.0.is_disliked'));

        $response = $this->actingAs($user)->json($this->method, route('comments.index', ['journal', $content->id]));
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertArrayHasKey('dislikes_count', $response->json('data.0'));
        $this->assertEquals(2, $response->json('data.0.dislikes_count'));
        $this->assertArrayHasKey('is_disliked', $response->json('data.0'));
        $this->assertTrue($response->json('data.0.is_disliked'));
    }
}
