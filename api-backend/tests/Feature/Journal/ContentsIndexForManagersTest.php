<?php

namespace Tests\Feature\Journal;

use App\Models\EventRegistration;
use App\Models\Institution\Institution;
use App\Models\Journal\ContentType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentsIndexForManagersTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";
    private Institution $institution;
    private User $institutionManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->institution = $this->createInstitution();
        $this->endpoint = route('institutions.contents.management.index', [$this->institution]);
        $this->institutionManager = $this->createManagerOfInstitution($this->institution);
    }

    /** @test */
    public function it_fetches_not_only_published_content(): void
    {
        $draft = $this->createArticle([
            'is_published' => false,
            'institution_id' => $this->institution->id
        ]);
        $draftOfAnotherInstitution = $this->createArticle([
            'is_published' => false,
            'institution_id' => $this->createInstitution()->id
        ]);
        $published = $this->createArticle([
            'is_published' => true,
            'institution_id' => $this->institution->id
        ]);

        $this->assertDatabaseCount('contents', 3);

        $response = $this->actingAs($this->institutionManager)->json($this->method, $this->endpoint);
        $response->assertOk();

        $fetchedData = $response->json('data');
        $this->assertCount(2, $fetchedData);
        foreach ($fetchedData as $item) {
            if ($item['id'] === $draft['id']) {
                $this->assertFalse($item['is_published']);
            }
            if ($item['id'] === $published['id']) {
                $this->assertTrue($item['is_published']);
            }
            $this->assertNotEquals($item['id'], $draftOfAnotherInstitution->id);
        }
    }

    /** @test */
    public function only_manager_of_institution_can_access_endpoint(): void
    {
        $this->json($this->method, $this->endpoint)->assertUnauthorized();
        $this->actingAs($this->createUser())->json($this->method, $this->endpoint)->assertForbidden();
        $this->actingAs($this->createManagerOfInstitution($this->createInstitution()))
            ->json($this->method, $this->endpoint)
            ->assertForbidden();
        $this->actingAs($this->institutionManager)->json($this->method, $this->endpoint)->assertOk();
    }

    /** @test */
    public function it_fetches_events_in_past(): void
    {
        $this->actingAs($this->institutionManager);
        $this->createArticle(['institution_id' => $this->institution->id]);
        $this->createNewsArticle(['institution_id' => $this->institution->id]);
        $this->createEvent([
            'date_end' => Carbon::yesterday(),
            'institution_id' => $this->institution->id
        ]);

        $this->assertDatabaseCount('contents', 3);

        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();

        $this->assertCount(3, $response->json('data'));

        $response = $this->json($this->method, $this->endpoint, ['type' => ContentType::EVENT]);
        $response->assertOk();

        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function it_fetches_contents_of_employer(): void
    {
        $employer = $this->createEmployer();
        $this->createNewsArticle(['user_id' => $employer->user->id]);

        $response = $this->actingAs($employer->user)
            ->json($this->method, route('employer.management.journal.index', $employer));
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function it_fetches_event_registrations_count(): void
    {
        $event = $this->createEvent(['institution_id' => $this->institution->id]);
        $this->createEventRegistration([
            'event_id' => $event->id,
            'status' => EventRegistration::STATUS_PENDING
        ]);
        $this->createEventRegistration([
            'event_id' => $event->id,
            'status' => EventRegistration::STATUS_ACCEPTED
        ]);
        $this->createEventRegistration([
            'event_id' => $event->id,
            'status' => EventRegistration::STATUS_REJECTED
        ]);

        $response = $this->actingAs($this->institutionManager)->json($this->method, $this->endpoint);
        $response->assertOk();
        $this->assertEquals(3, $response->json('data.0.registrations_count'));
        $this->assertEquals(2, $response->json('data.0.processed_registrations_count'));
    }
}
