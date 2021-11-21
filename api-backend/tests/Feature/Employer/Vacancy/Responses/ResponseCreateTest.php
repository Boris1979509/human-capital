<?php

namespace Tests\Feature\Employer\Vacancy\Responses;

use App\Models\Employer\Employer;
use App\Models\Employer\ResponseState\ResponseSendState;
use App\Models\Employer\VacancyResponse;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResponseCreateTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "post";
    private Vacancy $vacancy;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vacancy = $this->createVacancy();
        $this->user = $this->createUser();
        $this->endpoint = route('user.vacancies.responses.create', [$this->vacancy]);
    }

    /** @test */
    public function user_can_create_response_to_vacancy(): void
    {
        $responseData = [
            'cv_type' => VacancyResponse::CV_TYPE_GENERATED,
            'covering_letter' => 'some covering letter text'
        ];

        $this->assertDatabaseCount('vacancy_responses', 0);

        $response = $this->actingAs($this->user)->json($this->method, $this->endpoint, $responseData);
        $response->assertCreated();

        $this->assertDatabaseCount('vacancy_responses', 1);
        $this->assertDatabaseHas('vacancy_responses', array_merge($responseData, [
            'vacancy_id' => $this->vacancy->id,
            'user_id' => $this->user->id,
            'status' => ResponseSendState::$name,
        ]));
    }

    /** @test */
    public function user_can_not_create_response_to_already_applied_vacancy(): void
    {
        $responseData = [
            'cv_type' => VacancyResponse::CV_TYPE_GENERATED,
            'covering_letter' => 'some covering letter text'
        ];

        $this->createVacancyResponse([
            'user_id' => $this->user->id,
            'vacancy_id' => $this->vacancy->id
        ]);

        $this->assertDatabaseCount('vacancy_responses', 1);

        $response = $this->actingAs($this->user)->json($this->method, $this->endpoint, $responseData);
        $response->assertForbidden();

        $this->assertDatabaseCount('vacancy_responses', 1);
    }

    /** @test */
    public function user_can_create_response_with_uploaded_cv_to_vacancy(): void
    {
        Storage::fake('public');

        $cvMedia = $this->user->addMedia(UploadedFile::fake()->create('cv.pf'))
            ->toMediaCollection('job');

        $responseData = [
            'cv_type' => VacancyResponse::CV_TYPE_UPLOADED,
            'covering_letter' => 'some covering letter text',
            'cv_file_id' => $cvMedia->id,
        ];

        $this->assertDatabaseCount('vacancy_responses', 0);

        $response = $this->actingAs($this->user)->json($this->method, $this->endpoint, $responseData);
        $response->assertCreated();

        $this->assertDatabaseCount('vacancy_responses', 1);
        $this->assertDatabaseHas('vacancy_responses', array_merge($responseData, [
            'vacancy_id' => $this->vacancy->id,
            'user_id' => $this->user->id,
            'status' => ResponseSendState::$name,
        ]));
    }

    /** @test */
    public function only_authorized_user_can_create_response_to_vacancy(): void
    {
        $responseData = [
            'cv_type' => VacancyResponse::CV_TYPE_GENERATED,
            'covering_letter' => 'some covering letter text'
        ];
        $this->json($this->method, $this->endpoint, $responseData)->assertUnauthorized();
    }
}
