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

class EventRegistrationAcceptTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "post";
    private User $user;
    private Content $event;
    private EventRegistration $registration;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
        $this->event = $this->createEvent(['user_id' => $this->user->id]);
        $this->registration = $this->createEventRegistration(['event_id' => $this->event->id]);
        $this->endpoint = route('event-registrations.accept');
    }

    /** @test */
    public function only_authorized_user_can_access_this_endpoint(): void
    {
        $this->json($this->method, $this->endpoint)->assertUnauthorized();
    }

    /** @test */
    public function it_accepts_event_registration(): void
    {
        $this->actingAs($this->user);
        $response = $this->json($this->method, $this->endpoint, ['ids' => [$this->registration->id]]);
        $response->assertOk();
        $this->assertEquals(EventRegistration::STATUS_ACCEPTED, $this->registration->fresh()->status);
    }

    /** @test */
    public function it_doesnt_allow_to_change_status_of_others_registrations(): void
    {
        $this->actingAs($this->user);
        $response = $this->json($this->method, $this->endpoint, [
            'ids' => [
                $this->createEventRegistration()->id,
                $this->registration->id,
            ]
        ]);
        $response->assertForbidden();
    }
}
