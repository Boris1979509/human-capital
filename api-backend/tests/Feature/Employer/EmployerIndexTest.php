<?php

namespace Tests\Feature\Employer;

use App\Http\Resources\Employer\EmployerPublicCardResource;
use App\Models\Employer\Employer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployerIndexTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";

    protected function setUp(): void
    {
        parent::setUp();
        $this->endpoint = route('employer.public.index');
    }

    /** @test */
    public function it_fetches_employers(): void
    {
        $this->createEmployer();
        $this->createEmployer();

        $this->assertDatabaseCount('employers', 2);

        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();

        $this->assertCount(2, $response->json('data'));
        $this->assertEquals(
            (EmployerPublicCardResource::collection(Employer::simplePaginate()))->response()->getData(true),
            $response->json()
        );
    }

    /** @test */
    public function it_fetches_vacancies_count(): void
    {
        $employer = $this->createEmployer();
        $this->createVacancyForEmployer($employer);
        $this->createVacancyForEmployer($employer);

        $this->assertEquals(2, $employer->vacancies()->count());

        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();

        $this->assertCount(1, $response->json('data'));
        $this->assertEquals(2, $response->json('data.0.vacancies_count'));
    }

    /** @test */
    public function it_filters_employers_by_query(): void
    {
        $yandex = $this->createEmployer(null, ['name' => 'yandex']);
        $google = $this->createEmployer(null, ['name' => 'google']);

        $this->assertDatabaseCount('employers', 2);

        $response = $this->json($this->method, $this->endpoint, ['q' => 'y']);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($yandex->id, $response->json('data.0.id'));

        $response = $this->json($this->method, $this->endpoint, ['q' => 'l']);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($google->id, $response->json('data.0.id'));
    }

    /** @test */
    public function it_filters_employers_by_branch_id(): void
    {
        $redBranch = $this->createDictionary();
        $greenBranch = $this->createDictionary();
        $blueBranch = $this->createDictionary();

        $redEmployer = $this->createEmployer(null, ['branch_id' => $redBranch->id]);
        $greenEmployer = $this->createEmployer(null, ['branch_id' => $greenBranch->id]);
        $blueEmployer = $this->createEmployer(null, ['branch_id' => $blueBranch->id]);

        $response = $this->json($this->method, $this->endpoint, ['branch_id' => [$redBranch->id]]);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($redEmployer->id, $response->json('data.0.id'));

        $response = $this->json($this->method, $this->endpoint, ['branch_id' => [$greenBranch->id, $blueBranch->id]]);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals($greenEmployer->id, $response->json('data.0.id'));
        $this->assertEquals($blueEmployer->id, $response->json('data.1.id'));
    }

    /** @test */
    public function it_filters_employers_by_city(): void
    {
        $moscow = $this->createCity();
        $tyumen = $this->createCity();

        $moscowEmployer = $this->createEmployer(null, ['city_id' => $moscow->id]);
        $tyumenEmployer = $this->createEmployer(null, ['city_id' => $tyumen->id]);

        $response = $this->json($this->method, $this->endpoint, ['city_id' => [$moscow->id]]);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($moscowEmployer->id, $response->json('data.0.id'));

        $response = $this->json($this->method, $this->endpoint, ['city_id' => [$moscow->id, $tyumen->id]]);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals($moscowEmployer->id, $response->json('data.0.id'));
        $this->assertEquals($tyumenEmployer->id, $response->json('data.1.id'));
    }
}
