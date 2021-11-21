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

class UsersResponsesIndexTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
        $this->endpoint = route('user.responses.index');
    }

    /** @test */
    public function user_can_fetch_his_responses(): void
    {
        $response1 = $this->createVacancyResponse(['user_id' => $this->user->id]);
        $response2 = $this->createVacancyResponse(['user_id' => $this->user->id]);
        $this->createVacancyResponse();

        $this->assertDatabaseCount('vacancy_responses', 3);

        $response = $this->actingAs($this->user)->json($this->method, $this->endpoint);
        $response->assertOk();

        $this->assertCount(2, $response->json('data'));
        $this->assertEquals($response1->id, $response->json('data.0.id'));
        $this->assertEquals($response2->id, $response->json('data.1.id'));
    }

    /** @test */
    public function it_doesnt_fetch_deleted_by_user_responses(): void
    {
        $vacancyResponse = $this->createVacancyResponse([
            'user_id' => $this->user->id,
            'deleted_by_user' => false
        ]);
        $this->createVacancyResponse([
            'user_id' => $this->user->id,
            'deleted_by_user' => true
        ]);

        $this->assertDatabaseCount('vacancy_responses', 2);
        $this->assertEquals(2, $this->user->responses()->count());

        $response = $this->actingAs($this->user)->json($this->method, $this->endpoint);
        $response->assertOk();

        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($vacancyResponse->id, $response->json('data.0.id'));
    }

    /** @test */
    public function only_authorized_user_can_fetch_his_responses(): void
    {
        $this->json($this->method, $this->endpoint)->assertUnauthorized();
    }
}
