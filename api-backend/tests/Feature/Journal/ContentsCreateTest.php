<?php

namespace Tests\Feature\Journal;

use App\Models\Journal\Content;
use App\Models\Journal\ContentType;
use App\Models\Journal\EventSpeaker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Queue;
use Storage;
use Tests\TestCase;

class ContentsCreateTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "post";
    private User $institutionManager;

    protected function setUp(): void
    {
        parent::setUp();
        $institution = $this->createInstitution();
        $this->institutionManager = $this->createManagerOfInstitution($institution);
        $this->endpoint = route('institutions.contents.create', [$institution]);
    }

    /** @test */
    public function it_creates_news(): void
    {
        $this->actingAs($this->institutionManager);
        $newsData = Content::factory()->event()->make(['type' => ContentType::NEWS])->toArray();
        $this->assertNotEmpty($newsData['date_start']);

        $this->assertDatabaseCount('contents', 0);

        $response = $this->json($this->method, $this->endpoint, $newsData);
        $response->assertCreated();

        $this->assertDatabaseCount('contents', 1);
        $createdNews = Content::first();
        $this->assertEquals(ContentType::NEWS, $createdNews->type);
        $this->assertNull($createdNews->date_start);
        $this->assertEquals($createdNews->id, $response->json('id'));
    }

    /** @test */
    public function it_creates_event(): void
    {
        $this->actingAs($this->institutionManager);
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
        $this->assertNotNull($createdNews->date_start);
        $this->assertEquals($createdNews->id, $response->json('id'));
    }

    /** @test */
    public function it_generates_slug_from_title(): void
    {
        $this->actingAs($this->institutionManager);
        $articleData = Content::factory()->make()->toArray();

        $this->assertDatabaseCount('contents', 0);

        $response = $this->json($this->method, $this->endpoint, $articleData);
        $response->assertCreated();

        $this->assertDatabaseCount('contents', 1);
        $createdArticle = Content::first();
        $this->assertEquals($createdArticle->id, $response->json('id'));
        $this->assertEquals(Str::slug($articleData['title']), $createdArticle->slug);
    }

    /** @test */
    public function only_manager_of_institution_can_create_content(): void
    {
        $regularUser = $this->createUser();
        $managerOfAnotherInstitution = $this->createManagerOfInstitution($this->createInstitution());

        $newsData = Content::factory()->make()->toArray();

        $this->json($this->method, $this->endpoint, $newsData)->assertUnauthorized();

        $this->actingAs($regularUser)
            ->json($this->method, $this->endpoint, $newsData)
            ->assertForbidden();

        $this->actingAs($managerOfAnotherInstitution)
            ->json($this->method, $this->endpoint, $newsData)
            ->assertForbidden();

        $this->actingAs($this->institutionManager)
            ->json($this->method, $this->endpoint, $newsData)
            ->assertCreated();
    }

    /** @test */
    public function it_attaches_speakers_to_event(): void
    {
        Queue::fake();
        Storage::fake('public');
        $this->actingAs($this->institutionManager);

        $eventData = Content::factory()->event()->withEventData()->make()->toArray();

        $speakers = EventSpeaker::factory()->count(2)->make();
        $speakers = $speakers->map(function (EventSpeaker $speaker) {
            $speakerData = $speaker->toArray();
            $speakerData['avatar'] = $this->createTemporaryUpload()->id;
            return $speakerData;
        });
        $eventData['speakers'] = $speakers->toArray();
        $eventData['tags'] = [1];

        $this->assertDatabaseCount('contents', 0);
        $this->assertDatabaseCount('event_speakers', 0);
        $this->assertDatabaseCount('media', 2);

        $response = $this->json($this->method, $this->endpoint, $eventData);
        $response->assertCreated();

        $this->assertDatabaseCount('contents', 1);
        $this->assertDatabaseCount('event_speakers', 2);
        $createdNews = Content::first();
        $this->assertEquals(2, $createdNews->speakers()->count());
        $this->assertDatabaseCount('temporary_uploads', 0);
        $this->assertDatabaseCount('media', 2);
        foreach ($createdNews->speakers as $speaker) {
            $this->assertNotNull($speaker->getFirstMedia('avatar'));
        }
        $this->assertCount(2, Storage::disk('public')->allFiles());
    }

    /** @test */
    public function it_saves_images_to_content(): void
    {
        Storage::fake('public');
        Queue::fake();
        $this->actingAs($this->institutionManager);
        $eventData = Content::factory()->event()->withSpeakers()->withEventData()->make()->toArray();
        $eventData['images'] = [
            $this->createTemporaryUpload()->id,
            $this->createTemporaryUpload()->id,
        ];
        $eventData['tags'] = [1];

        $response = $this->json($this->method, $this->endpoint, $eventData);

        $response->assertCreated();

        $createdContent = Content::first();
        $this->assertEquals(2, $createdContent->getMedia('images')->count());
        $media = $createdContent->fresh()->getFirstMedia('images');
        Storage::disk('public')
            ->assertExists($media->id.'/'.$media->file_name);
        $this->assertDatabaseCount('temporary_uploads', 0);
        $this->assertCount(2, Storage::disk('public')->allFiles());
    }

    /** @test */
    public function it_sets_published_at_field(): void
    {
        $this->actingAs($this->institutionManager);
        $newsData = Content::factory()->make(['type' => ContentType::NEWS])->toArray();

        $response = $this->json($this->method, $this->endpoint, $newsData);
        $response->assertCreated();

        $this->assertDatabaseCount('contents', 1);
        $createdNews = Content::first();
        $this->assertNotNull($createdNews->published_at);
    }
}
