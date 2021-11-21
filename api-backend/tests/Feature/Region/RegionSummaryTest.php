<?php

namespace Tests\Feature\Region;

use App\Models\RegionSummary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegionSummaryTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";

    protected function setUp(): void
    {
        parent::setUp();
        $this->endpoint = route('region.summary');
    }

    /** @test */
    public function it_fetches_region_summary(): void
    {
        $this->createInstitution();
        $this->createInstitution();

        $this->assertDatabaseCount('institutions', 2);

        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();

        $data = $response->json();

        $this->assertEquals(RegionSummary::INSTITUTION_SUMMARY_ID, $data[0]['id']);
        $this->assertEquals(2, $data[0]['count']);

        $this->assertEquals(RegionSummary::VACANCIES_SUMMARY_ID, $data[1]['id']);
        //TODO: тест счетчиков вакансий

        $this->assertEquals(RegionSummary::SALARY_SUMMARY_ID, $data[2]['id']);
        //TODO: тест счетчиков зарплаты
    }
}
