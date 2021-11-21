<?php

namespace Tests\Feature\Journal;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentsSimilarTest extends TestCase
{
    use RefreshDatabase;

    private string $routeName = 'contents.similar';
    private string $method = "get";

    /** @test */
    public function it_fetches_similar_content_for_news(): void
    {
        $news = $this->createNewsArticle();
        $similarNews = $this->createNewsArticle();
        $this->createEvent();
        $this->createArticle();

        $this->assertDatabaseCount('contents', 4);

        $response = $this->json($this->method, route($this->routeName, [$news]));
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($similarNews->id, $response->json('data.0.id'));
    }

    /** @test */
    public function it_fetches_similar_content_for_articles(): void
    {
        $article = $this->createArticle();
        $similarArticle = $this->createArticle();
        $this->createEvent();
        $this->createNewsArticle();

        $this->assertDatabaseCount('contents', 4);

        $response = $this->json($this->method, route($this->routeName, [$article]));
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($similarArticle->id, $response->json('data.0.id'));
    }

    /** @test */
    public function it_fetches_similar_content_for_events(): void
    {
        $event = $this->createEvent();
        $similarEvent = $this->createEvent();
        $this->createNewsArticle();
        $this->createArticle();

        $this->assertDatabaseCount('contents', 4);

        $response = $this->json($this->method, route($this->routeName, [$event]));
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($similarEvent->id, $response->json('data.0.id'));
    }
}
