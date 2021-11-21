<?php

namespace Tests\Feature\EventRegistration;

use App\Models\Employer\Employer;
use App\Models\EventRegistration;
use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionCurriculum;
use App\Models\Journal\Content;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class EventRegistrationIndexTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";
    private User $user;
    private Content $event;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
        $this->event = $this->createEvent(['user_id' => $this->user->id]);
        $this->endpoint = route('event-registrations.index', $this->event->id);
    }

    /** @test */
    public function unauthorized_user_cannot_access_endpoint(): void
    {
        $this->json($this->method, $this->endpoint)->assertUnauthorized();
    }

    /** @test */
    public function it_fetches_event_registrations(): void
    {
        $this->createEventRegistration();
        $registration = $this->createEventRegistration([
            'event_id' => $this->event->id,
        ]);

        $this->assertDatabaseCount(EventRegistration::class, 2);

        $this->actingAs($this->user);

        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();
        $this->assertCount(1, $response->json('registrations'));
        $this->assertEquals($registration->id, $response->json('registrations.0.id'));
        $this->assertEquals($this->event->title, $response->json('event.title'));
    }

    /** @test */
    public function only_manager_of_event_can_fetch_registrations(): void
    {
        $this->actingAs($this->createUser())->json($this->method, $this->endpoint)->assertForbidden();
    }

}
