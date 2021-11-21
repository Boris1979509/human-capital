<?php

namespace Tests\Feature\Employer\Vacancy;

use App\Http\Resources\Employer\VacancyDetailedPublicResource;
use App\Http\Resources\Employer\VacancyResource;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VacancyShowPublicTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";
    private Vacancy $vacancy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vacancy = $this->createVacancy();
        $this->endpoint = route('vacancies.public.show', [$this->vacancy]);
    }

    /** @test */
    public function it_fetches_vacancy(): void
    {
        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();

        $this->assertEquals(
            (new VacancyDetailedPublicResource($this->vacancy))->response(null)->getData(true),
            $response->json()
        );
    }

    /** @test */
    public function it_marks_vacancy_as_applied_for_user(): void
    {
        $appliedUser = $this->createUser();
        $someUser = $this->createUser();

        $this->createVacancyResponse(['vacancy_id' => $this->vacancy->id, 'user_id' => $appliedUser->id]);

        $this->assertTrue($this->vacancy->isUserApplied($appliedUser->id));
        $this->assertFalse($this->vacancy->isUserApplied($someUser->id));

        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();
        $this->assertArrayNotHasKey('is_user_applied', $response->json('data'));

        $response = $this->actingAs($someUser)->json($this->method, $this->endpoint);
        $response->assertOk();
        $this->assertFalse($response->json('data.is_user_applied'));

        $response = $this->actingAs($appliedUser)->json($this->method, $this->endpoint);
        $response->assertOk();
        $this->assertTrue($response->json('data.is_user_applied'));
    }

    /** @test */
    public function it_records_views(): void
    {
        $this->assertEquals(0, views($this->vacancy)->count());

        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();

        $this->assertEquals(1, views($this->vacancy)->count());

        $this->assertEquals(
            1,
            (new VacancyResource($this->vacancy))->response()->getData(true)['data']['views']
        );
    }
}
