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

class EventRegistrationDeleteTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "delete";
    private User $user;
    private Content $event;
    private EventRegistration $registration;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
        $this->event = $this->createEvent(['user_id' => $this->user->id]);
        $this->registration = $this->createEventRegistration(['event_id' => $this->event->id]);
        $this->endpoint = route('event-registrations.delete');
    }

    /** @test */
    public function only_authorized_user_can_access_this_endpoint(): void
    {
        $this->json($this->method, $this->endpoint)->assertUnauthorized();
    }

    /** @test */
    public function it_deletes_event_registration(): void
    {
        $this->actingAs($this->user);
        $response = $this->json($this->method, $this->endpoint, ['ids'=>[$this->registration->id]]);
        $response->assertNoContent();
        $this->assertDatabaseMissing(EventRegistration::class, ['id' => $this->registration->id]);
    }

    /** @test */
    public function it_doesnt_allow_to_delete_others_registrations(): void
    {
        $this->actingAs($this->createUser());
        $response = $this->json($this->method, $this->endpoint, ['ids'=>[$this->registration->id]]);
        $response->assertForbidden();
    }
}
