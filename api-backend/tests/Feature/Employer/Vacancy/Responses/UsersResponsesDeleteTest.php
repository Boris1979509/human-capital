<?php

namespace Tests\Feature\Employer\Vacancy\Responses;

use App\Models\Employer\Employer;
use App\Models\Employer\VacancyResponse;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UsersResponsesDeleteTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "delete";
    private User $user;
    private VacancyResponse $response;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
        $this->response = $this->createVacancyResponse(['user_id' => $this->user->id]);
        $this->endpoint = route('user.responses.delete', [$this->response]);
    }

    /** @test */
    public function user_can_fetch_his_responses(): void
    {
        $this->assertDatabaseCount('vacancy_responses', 1);

        $response = $this->actingAs($this->user)->json($this->method, $this->endpoint);
        $response->assertNoContent();

        $this->assertDatabaseCount('vacancy_responses', 1);
        $this->assertTrue($this->response->fresh()->deleted_by_user);
    }

    /** @test */
    public function only_user_who_created_response_can_delete_it(): void
    {
        $this->assertFalse($this->response->fresh()->deleted_by_user);

        $this->json($this->method, $this->endpoint)->assertUnauthorized();
        $this->actingAs($this->createUser())->json($this->method, $this->endpoint)->assertForbidden();
        $this->actingAs($this->response->vacancy->employer->user)
            ->json($this->method, $this->endpoint)
            ->assertForbidden();

        $this->assertFalse($this->response->fresh()->deleted_by_user);
    }

    /** @test */
    public function deletion_of_vacancy_is_idempotent(): void
    {
        $this->response->hideFromUser();
        $this->assertTrue($this->response->fresh()->deleted_by_user);

        $response = $this->actingAs($this->user)->json($this->method, $this->endpoint);
        $response->assertNoContent();

        $this->assertTrue($this->response->fresh()->deleted_by_user);
    }
}
