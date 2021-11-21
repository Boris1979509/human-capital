<?php

namespace Tests\Feature\Employer\Vacancy;

use App\Models\Employer\Employer;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VacancyCreateTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "post";
    private Employer $employer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->employer = $this->createEmployer();
        $this->endpoint = route('employer.management.vacancies.create', [$this->employer]);
    }

    /** @test */
    public function employer_can_create_vacancy(): void
    {
        $this->actingAs($this->employer->user);

        $this->assertDatabaseCount('vacancies', 0);

        $response = $this->json($this->method, $this->endpoint, Vacancy::factory()->make()->toArray());
        $response->assertCreated();


        $this->assertDatabaseCount('vacancies', 1);
        $this->assertDatabaseHas('vacancies', [
            'id' => $response->json('data.id')
        ]);
    }

    /** @test */
    public function only_employer_can_create_vacancy(): void
    {
        $vacancyData = Vacancy::factory()->make()->toArray();
        $this->json($this->method, $this->endpoint, $vacancyData)->assertUnauthorized();

        $institutionManager = $this->createManagerOfInstitution($this->createInstitution());
        $this->actingAs($institutionManager)->json($this->method, $this->endpoint, $vacancyData)->assertForbidden();
    }
}
