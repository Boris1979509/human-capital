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

class EventRegistrationShowTest extends TestCase
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
        $this->endpoint = route('event-registrations.show', $this->event->id);
    }

    /** @test */
    public function unauthorized_user_cannot_access_endpoint(): void
    {
        $this->json($this->method, $this->endpoint)->assertUnauthorized();
    }

    /** @test */
    public function it_fetches_info_about_event_registration(): void
    {
        $registration = $this->createEventRegistration([
            'user_id' => $this->user->id,
            'event_id' => $this->event->id,
        ]);

        $response = $this->actingAs($this->user)->json($this->method, $this->endpoint);
        $response->assertOk();

        $this->assertNotNull($response->json('data'));
        $this->assertEquals($registration->id, $response->json('data.id'));
        $this->assertEquals($registration->status, $response->json('data.status'));

        $response = $this->actingAs($this->createUser())->json($this->method, $this->endpoint);
        $response->assertNotFound();
    }

}
