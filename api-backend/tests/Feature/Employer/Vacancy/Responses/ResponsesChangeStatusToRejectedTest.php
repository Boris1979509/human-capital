<?php

namespace Tests\Feature\Employer\Vacancy\Responses;

use App\Models\Employer\Employer;
use App\Models\Employer\ResponseState\ApplicantInvitedState;
use App\Models\Employer\ResponseState\ApplicantRejectedState;
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

class ResponsesChangeStatusToRejectedTest extends TestCase
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
        $this->endpoint = route('employer.responses.reject', [$this->response]);
        $this->vacancyManager = $this->response->vacancy->employer->user;
    }

    /** @test */
    public function it_changes_status_to_rejected(): void
    {
        $this->withoutExceptionHandling();
        $this->assertDatabaseCount('vacancy_responses', 1);
        $this->assertEquals(ResponseSendState::$name, $this->response->status);

        $interviewType = $this->createDictionary();

        $rejectionData = [
            'message' => 'message',
        ];

        $response = $this->actingAs($this->vacancyManager)->json($this->method, $this->endpoint, $rejectionData);
        $response->assertNoContent();

        $this->response = $this->response->fresh();

        $this->assertInstanceOf(ApplicantRejectedState::class, $this->response->status);
        $this->assertEquals(
            [
                'message' => 'message',
            ],
            $this->response->rejection
        );
    }

    /** @test */
    public function only_manager_of_vacancy_can_change_state(): void
    {
        $this->assertEquals(ResponseSendState::$name, $this->response->status);

        $rejectionData = [
            'message' => 'message',
        ];
        $this->json($this->method, $this->endpoint, $rejectionData)->assertUnauthorized();
        $this->actingAs($this->createUser())->json($this->method, $this->endpoint, $rejectionData)->assertForbidden();
        $this->actingAs($this->response->applicant)
            ->json($this->method, $this->endpoint, $rejectionData)
            ->assertForbidden();

        $this->assertEquals(ResponseSendState::$name, $this->response->status);
    }
}
