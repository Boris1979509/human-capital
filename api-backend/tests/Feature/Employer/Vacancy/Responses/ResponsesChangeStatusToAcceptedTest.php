<?php

namespace Tests\Feature\Employer\Vacancy\Responses;

use App\Models\Employer\Employer;
use App\Models\Employer\ResponseState\ApplicantInvitedState;
use App\Models\Employer\ResponseState\ResponseSeenState;
use App\Models\Employer\ResponseState\ResponseSendState;
use App\Models\Employer\VacancyResponse;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResponsesChangeStatusToAcceptedTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "post";
    private VacancyResponse $response;
    private User $vacancyManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->response = $this->createVacancyResponse();
        $this->endpoint = route('employer.responses.invite', [$this->response]);
        $this->vacancyManager = $this->response->vacancy->employer->user;
    }

    /** @test */
    public function it_changes_status_to_seen(): void
    {
        $this->withoutExceptionHandling();
        $this->assertDatabaseCount('vacancy_responses', 1);
        $this->assertEquals(ResponseSendState::$name, $this->response->status);

        $interviewType = $this->createDictionary();

        $inviteData = [
            'message' => 'message',
            'interview_type_id' => $interviewType->id,
            'contact_phone' => 'phone'
        ];

        $response = $this->actingAs($this->vacancyManager)->json($this->method, $this->endpoint, $inviteData);
        $response->assertNoContent();

        $this->response = $this->response->fresh();

        $this->assertInstanceOf(ApplicantInvitedState::class, $this->response->status);
        $this->assertEquals(
            [
                'message' => 'message',
                'interview_type' => [
                    'id' => $interviewType->id,
                    'name' => $interviewType->option
                ],
                'contact_phone' => 'phone'
            ],
            $this->response->invite
        );
    }

    /** @test */
    public function only_manager_of_vacancy_can_change_state(): void
    {
        $this->assertEquals(ResponseSendState::$name, $this->response->status);

        $inviteData = [
            'message' => 'message',
            'interview_type_id' => $this->createDictionary()->id,
            'contact_phone' => 'phone'
        ];
        $this->json($this->method, $this->endpoint, $inviteData)->assertUnauthorized();
        $this->actingAs($this->createUser())->json($this->method, $this->endpoint, $inviteData)->assertForbidden();
        $this->actingAs($this->response->applicant)
            ->json($this->method, $this->endpoint, $inviteData)
            ->assertForbidden();

        $this->assertEquals(ResponseSendState::$name, $this->response->status);
    }
}
