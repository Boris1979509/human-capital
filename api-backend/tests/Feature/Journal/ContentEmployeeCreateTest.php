<?php

namespace Tests\Feature\Journal;

use App\Models\Employer\Employer;
use App\Models\Journal\Content;
use App\Models\Journal\ContentType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentEmployeeCreateTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "post";
    private Employer $employer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->employer = $this->createEmployer();
        $this->endpoint = route('employer.management.journal.create', [$this->employer]);
    }

    /** @test */
    public function it_creates_news(): void
    {
        $this->actingAs($this->employer->user);
        $newsData = Content::factory()->event()->make(['type' => ContentType::NEWS])->toArray();
        $this->assertNotEmpty($newsData['date_start']);

        $this->assertDatabaseCount('contents', 0);

        $response = $this->json($this->method, $this->endpoint, $newsData);
        $response->assertCreated();

        $this->assertDatabaseCount('contents', 1);
        $createdNews = Content::first();
        $this->assertNull($createdNews->institution_id);
        $this->assertEquals($this->employer->user->id, $createdNews->user_id);
        $this->assertEquals(ContentType::NEWS, $createdNews->type);
        $this->assertNull($createdNews->date_start);
        $this->assertEquals($createdNews->id, $response->json('id'));
    }

    /** @test */
    public function it_creates_event(): void
    {
        $this->actingAs($this->employer->user);
        $eventData = Content::factory()->event()->withSpeakers()->withEventData()->make()->toArray();
        $eventData['tags'] = [1];
        $this->assertNotEmpty($eventData['date_start']);
        $eventData['date_end'] = '2021-03-01T00:00:00.000Z';

        $this->assertDatabaseCount('contents', 0);

        $response = $this->json($this->method, $this->endpoint, $eventData);
        $response->assertCreated();

        $this->assertDatabaseCount('contents', 1);
        $createdNews = Content::first();
        $this->assertEquals(ContentType::EVENT, $createdNews->type);
        $this->assertNull($createdNews->institution_id);
        $this->assertEquals($this->employer->user->id, $createdNews->user_id);
        $this->assertNotNull($createdNews->date_start);
        $this->assertEquals($createdNews->id, $response->json('id'));
    }

    /** @test */
    public function it_cannot_creates_article(): void
    {
        $this->actingAs($this->employer->user);
        $articleData = Content::factory()->make(['type' => ContentType::ARTICLE])->toArray();

        $this->assertDatabaseCount('contents', 0);

        $response = $this->json($this->method, $this->endpoint, $articleData);
        $response->assertForbidden();

        $this->assertDatabaseCount('contents', 0);
    }

    /** @test */
    public function only_employer_can_create_content(): void
    {
        $regularUser = $this->createUser();
        $newsData = Content::factory()->make()->toArray();

        $this->json($this->method, $this->endpoint, $newsData)->assertUnauthorized();

        $this->actingAs($regularUser)
            ->json($this->method, $this->endpoint, $newsData)
            ->assertForbidden();

        $this->actingAs($this->employer->user)
            ->json($this->method, $this->endpoint, $newsData)
            ->assertCreated();
    }
}
