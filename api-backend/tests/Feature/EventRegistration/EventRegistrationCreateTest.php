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

class EventRegistrationCreateTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "post";
    private User $user;
    private Content $event;

    protected function setUp(): void
    {
        parent::setUp();
        $this->event = $this->createEvent();
        $this->endpoint = route('event-registrations.create', $this->event->id);
        $this->user = $this->createUser();
    }

    /** @test */
    public function only_authorized_user_can_access_this_endpoint(): void
    {
        $this->json($this->method, $this->endpoint)->assertUnauthorized();
    }

    /** @test */
    public function it_creates_event_registration(): void
    {
        $this->assertDatabaseCount(EventRegistration::class, 0);

        $this->actingAs($this->user);

        $registrationData = [
            'fields' => [
                'name' => 'user name',
                'email' => 'user email',
                'phone' => 'user phone'
            ],
            'questions' => [
                'some question' => 'some answer'
            ]
        ];
        $response = $this->json($this->method, $this->endpoint, $registrationData);
        $response->assertCreated();

        $this->assertDatabaseCount(EventRegistration::class, 1);
        $this->assertDatabaseHas(EventRegistration::class, [
            'user_id' => $this->user->id,
            'event_id' => $this->event->id
        ]);
    }

    /** @test */
    public function it_validates_fields_and_questions(): void
    {
        $this->event->update([
            'registration_fields' => [
                'name',
                'email'
            ],
            'registration_questions' => [
                'some question',
                'another question'
            ]
        ]);

        $this->actingAs($this->user)
            ->json($this->method, $this->endpoint, [
                'fields' => [
                    'name' => 'name'
                ]
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('fields');

        $this->actingAs($this->user)
            ->json($this->method, $this->endpoint, [
                'fields' => [
                    'name' => 'name',
                    'email' => 'email'
                ],
                'questions' => [
                    'some question' => 'some answer',
                    'another question' => ''
                ]
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('questions');

        $this->assertDatabaseCount(EventRegistration::class, 0);
    }

    /** @test */
    public function it_doesnt_allow_to_register_at_event_when_it_has_no_available_slots(): void
    {
        $registrationData = [
            'fields' => [
                'name' => 'user name',
                'email' => 'user email',
                'phone' => 'user phone'
            ],
            'questions' => ['question' => 'answer']
        ];
        $this->event->update(['available_registration_slots' => 2]);
        $this->createEventRegistration([
            'status' => EventRegistration::STATUS_ACCEPTED,
            'event_id' => $this->event->id,
        ]);
        $this->actingAs($this->createUser())->json($this->method, $this->endpoint, $registrationData)->assertCreated();

        $this->assertDatabaseCount(EventRegistration::class, 2);

        $this->actingAs($this->user)->json($this->method, $this->endpoint, $registrationData)->assertCreated();

        $this->createEventRegistration([
            'status' => EventRegistration::STATUS_ACCEPTED,
            'event_id' => $this->event->id,
        ]);

        $this->assertDatabaseCount(EventRegistration::class, 4);

        $this->actingAs($this->user)->json($this->method, $this->endpoint, $registrationData)->assertForbidden();
    }

    /** @test */
    public function it_doesnt_allow_to_register_at_event_more_than_one_time(): void
    {
        $registrationData = [
            'fields' => [
                'name' => 'user name',
                'email' => 'user email',
                'phone' => 'user phone'
            ],
            'questions' => ['question' => 'answer']
        ];
        $this->actingAs($this->user)->json($this->method, $this->endpoint, $registrationData)->assertCreated();

        $this->assertDatabaseCount(EventRegistration::class, 1);

        $this->actingAs($this->user)->json($this->method, $this->endpoint, $registrationData)->assertForbidden();
    }

    /** @test */
    public function it_doesnt_allow_to_register_at_event_after_registration_expires(): void
    {
        $registrationData = [
            'fields' => [
                'name' => 'user name',
                'email' => 'user email',
                'phone' => 'user phone'
            ],
            'questions' => ['question' => 'answer']
        ];

        $this->event->update(['registration_available_till' => now()->subHour()]);

        $this->actingAs($this->user)->json($this->method, $this->endpoint, $registrationData)->assertForbidden();
    }
}
