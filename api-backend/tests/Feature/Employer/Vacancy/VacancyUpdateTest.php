<?php

namespace Tests\Feature\Employer\Vacancy;

use App\Models\Employer\Employer;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VacancyUpdateTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "put";
    private Employer $employer;
    private Vacancy $vacancy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->employer = $this->createEmployer();
        $this->vacancy = $this->createVacancyForEmployer($this->employer);
        $this->endpoint = route('employer.management.vacancies.update', [$this->vacancy]);
    }

    /** @test */
    public function employer_can_update_vacancy(): void
    {
        $this->actingAs($this->employer->user);

        $this->assertDatabaseCount('vacancies', 1);

        $response = $this->json($this->method, $this->endpoint, Vacancy::factory()->make()->toArray());
        $response->assertOk();


        $this->assertDatabaseCount('vacancies', 1);
        $this->assertDatabaseHas('vacancies', [
            'id' => $response->json('data.id')
        ]);
    }

    /** @test */
    public function only_employer_can_update_vacancy(): void
    {
        $vacancyData = Vacancy::factory()->make()->toArray();
        $this->json($this->method, $this->endpoint, $vacancyData)->assertUnauthorized();

        $institutionManager = $this->createManagerOfInstitution($this->createInstitution());
        $this->actingAs($institutionManager)->json($this->method, $this->endpoint, $vacancyData)->assertForbidden();

        $this->actingAs($this->createEmployer()->user)->json(
            $this->method,
            $this->endpoint,
            $vacancyData
        )->assertForbidden();
    }
}
