<?php

namespace Tests\Feature\Employer\Vacancy;

use App\Http\Resources\Employer\VacancyPublicCardResource;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VacancySimilarShowTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";
    private Vacancy $vacancy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vacancy = $this->createVacancy();
        $this->endpoint = route('vacancies.public.similar', [$this->vacancy]);
    }

    /** @test */
    public function it_fetches_similar_vacancies(): void
    {
        $similarVacancy = $this->createVacancy();
        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();

        $this->assertCount(1, $response->json('data'));
        $this->assertEquals(
            (VacancyPublicCardResource::collection([$similarVacancy]))->response(null)->getData(true)['data'],
            $response->json('data')
        );
    }
}
