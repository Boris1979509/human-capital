<?php

namespace Tests\Feature\Employer\Vacancy;

use App\Models\Employer\Employer;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VacancyDeleteTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "delete";
    private Vacancy $vacancy;
    private Employer $employer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->employer = $this->createEmployer();
        $this->vacancy = $this->createVacancyForEmployer($this->employer);
        $this->endpoint = route('employer.management.vacancies.delete', $this->vacancy);
    }

    /** @test */
    public function it_deletes_vacancy(): void
    {
        $this->actingAs($this->employer->user);

        $this->assertDatabaseCount('vacancies', 1);
        $response = $this->json($this->method, $this->endpoint);
        $response->assertNoContent();

        $this->assertDatabaseCount('vacancies', 0);
    }

    /** @test */
    public function only_employer_can_delete_vacancy(): void
    {
        $this->assertDatabaseCount('vacancies', 1);
        $this->json($this->method, $this->endpoint)->assertUnauthorized();
        $this->actingAs($this->createEmployer()->user)->json($this->method, $this->endpoint)->assertForbidden();
        $this->assertDatabaseCount('vacancies', 1);
    }
}
