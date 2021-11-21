<?php

namespace Tests\Feature\Employer\Vacancy;

use App\Http\Resources\Employer\VacancyDetailedResource;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VacancyShowTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";
    private Vacancy $vacancy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vacancy = $this->createVacancy();
        $this->endpoint = route('employer.management.vacancies.show', [$this->vacancy]);
    }

    /** @test */
    public function it_fetches_vacancy(): void
    {
        $response = $this->actingAs($this->vacancy->employer->user)->json($this->method, $this->endpoint);
        $response->assertOk();

        $this->assertEquals(
            (new VacancyDetailedResource($this->vacancy))->response(null)->getData(true),
            $response->json()
        );
    }

    /** @test */
    public function only_authorized_user_can_access_endpoint(): void
    {
        $this->json($this->method, $this->endpoint)->assertUnauthorized();
        $this->actingAs($this->createUser())->json($this->method, $this->endpoint)->assertForbidden();
    }
}
