<?php

namespace Tests\Feature\Employer;

use App\Models\Employer\Employer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployerSummaryTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";

    protected function setUp(): void
    {
        parent::setUp();
        $this->endpoint = route('employer.public.summary');
    }

    /** @test */
    public function it_fetches_employers_summary(): void
    {
        $this->createEmployer();
        $this->createEmployer();

        $this->createVacancy(['employer_id' => Employer::first()->id]);
        $this->createVacancy(['employer_id' => Employer::first()->id]);

        $this->assertDatabaseCount('employers', 2);
        $this->assertDatabaseCount('vacancies', 2);

        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();

        $this->assertEquals(2, $response->json('vacancies_count'));
        $this->assertEquals(2, $response->json('employers_count'));
    }
}
