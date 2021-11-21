<?php

namespace Tests\Feature\Employer\Vacancy\Responses;

use App\Models\Employer\Employer;
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

class ResponsesChangeStatusToSeenTest extends TestCase
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
        $this->endpoint = route('employer.responses.seen', [$this->response]);
        $this->vacancyManager = $this->response->vacancy->employer->user;
    }

    /** @test */
    public function it_changes_status_to_seen(): void
    {
        $this->assertDatabaseCount('vacancy_responses', 1);
        $this->assertEquals(ResponseSendState::$name, $this->response->status);

        $response = $this->actingAs($this->vacancyManager)->json($this->method, $this->endpoint);
        $response->assertNoContent();

        $this->assertInstanceOf(ResponseSeenState::class, $this->response->fresh()->status);
    }

    /** @test */
    public function only_manager_of_vacancy_can_change_state(): void
    {
        $this->assertEquals(ResponseSendState::$name, $this->response->status);

        $this->json($this->method, $this->endpoint)->assertUnauthorized();
        $this->actingAs($this->createUser())->json($this->method, $this->endpoint)->assertForbidden();
        $this->actingAs($this->response->applicant)
            ->json($this->method, $this->endpoint)
            ->assertForbidden();

        $this->assertEquals(ResponseSendState::$name, $this->response->status);
    }
}
