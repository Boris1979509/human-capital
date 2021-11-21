<?php

namespace Tests\Feature\Employer\Vacancy;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VacancyIndexTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";

    protected function setUp(): void
    {
        parent::setUp();
        $this->endpoint = route('vacancies.index');
    }

    /** @test */
    public function it_fetches_vacancies(): void
    {
        $this->createVacancy();
        $this->createVacancy();
        $this->assertDatabaseCount('vacancies', 2);

        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();

        $this->assertCount(2, $response->json('data'));
    }

    /** @test */
    public function it_filters_vacancies_by_employer_id(): void
    {
        $yandex = $this->createEmployer();
        $google = $this->createEmployer();

        $yandexVacancy = $this->createVacancyForEmployer($yandex);
        $this->createVacancyForEmployer($google);

        $response = $this->json($this->method, $this->endpoint, ['employer_id' => $yandex->id]);
        $response->assertOk();

        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($yandexVacancy->id, $response->json('data.0.id'));
    }

    /** @test */
    public function it_filters_vacancies_by_search_query(): void
    {
        $fooVacancy = $this->createVacancy(['name' => 'foo']);
        $barVacancy = $this->createVacancy(['name' => 'bar']);

        $response = $this->json($this->method, $this->endpoint, ['q' => 'o']);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($fooVacancy->id, $response->json('data.0.id'));

        $response = $this->json($this->method, $this->endpoint, ['q' => 'a']);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($barVacancy->id, $response->json('data.0.id'));
    }

    /** @test */
    public function it_filters_vacancies_by_employer_query(): void
    {
        $yandex = $this->createEmployer(null, ['name' => 'yandex']);
        $google = $this->createEmployer(null, ['name' => 'google']);

        $yandexVacancy = $this->createVacancyForEmployer($yandex);
        $googleVacancy = $this->createVacancyForEmployer($google);

        $response = $this->json($this->method, $this->endpoint, ['employer' => 'a']);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($yandexVacancy->id, $response->json('data.0.id'));

        $response = $this->json($this->method, $this->endpoint, ['employer' => 'o']);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($googleVacancy->id, $response->json('data.0.id'));
    }

    /** @test */
    public function it_filters_vacancies_by_profession_id(): void
    {
        $itProfession = $this->createProfession();
        $medicineProfession = $this->createProfession();

        $itVacancy = $this->createVacancy(['profession_id' => $itProfession->id]);
        $medicineVacancy = $this->createVacancy(['profession_id' => $medicineProfession->id]);
        $this->createVacancy();

        $response = $this->json($this->method, $this->endpoint, ['profession_id' => [$itProfession->id]]);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($itVacancy->id, $response->json('data.0.id'));

        $response = $this->json($this->method, $this->endpoint, [
            'profession_id' => [$itProfession->id, $medicineProfession->id]
        ]);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals($itVacancy->id, $response->json('data.0.id'));
        $this->assertEquals($medicineVacancy->id, $response->json('data.1.id'));
    }

    /** @test */
    public function it_filters_vacancies_by_city_id(): void
    {
        $moscow = $this->createCity();
        $tyumen = $this->createCity();

        $moscowVacancy = $this->createVacancy(['city_id' => $moscow->id]);
        $tyumenVacancy = $this->createVacancy(['city_id' => $tyumen->id]);
        $this->createVacancy();

        $response = $this->json($this->method, $this->endpoint, ['city_id' => [$moscow->id]]);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($moscowVacancy->id, $response->json('data.0.id'));

        $response = $this->json($this->method, $this->endpoint, [
            'city_id' => [$moscow->id, $tyumen->id]
        ]);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals($moscowVacancy->id, $response->json('data.0.id'));
        $this->assertEquals($tyumenVacancy->id, $response->json('data.1.id'));
    }

    /** @test */
    public function it_filters_vacancies_by_published_from_and_to_time(): void
    {
        $todayVacancy = $this->createVacancy(['created_at' => now()]);
        $yesterdayVacancy = $this->createVacancy(['created_at' => now()->subDay()]);

        $response = $this->json($this->method, $this->endpoint, ['published_from' => now()->subHour()->toDateString()]);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($todayVacancy->id, $response->json('data.0.id'));

        $response = $this->json($this->method, $this->endpoint, ['published_to' => now()->subHour()->toDateString()]);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($yesterdayVacancy->id, $response->json('data.0.id'));
    }

    /** @test */
    public function it_filters_vacancies_by_skills(): void
    {
        $vacancy1 = $this->createVacancy(['skills' => ['foo', 'bar', 'zar']]);
        $vacancy2 = $this->createVacancy(['skills' => ['foo']]);
        $this->createVacancy(['skills' => ['bar']]);

        $response = $this->json($this->method, $this->endpoint, ['skills' => ['foo']]);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals($vacancy1->id, $response->json('data.0.id'));
        $this->assertEquals($vacancy2->id, $response->json('data.1.id'));

        $response = $this->json($this->method, $this->endpoint, ['skills' => ['foo', 'bar']]);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($vacancy1->id, $response->json('data.0.id'));
    }

    /** @test */
    public function it_filters_vacancies_by_min_salary(): void
    {
        $vacancy100 = $this->createVacancy(['salary_min' => 100]);
        $vacancy500 = $this->createVacancy(['salary_min' => 500]);

        $response = $this->json($this->method, $this->endpoint, ['salary_min' => 50]);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals($vacancy100->id, $response->json('data.0.id'));
        $this->assertEquals($vacancy500->id, $response->json('data.1.id'));

        $response = $this->json($this->method, $this->endpoint, ['salary_min' => 200]);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($vacancy500->id, $response->json('data.0.id'));
    }

    /** @test */
    public function it_filters_vacancies_by_internship_status(): void
    {
        $internshipVacancy = $this->createVacancy(['is_internship' => true]);
        $regularVacancy = $this->createVacancy(['is_internship' => false]);

        $response = $this->json($this->method, $this->endpoint, ['internship' => true]);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($internshipVacancy->id, $response->json('data.0.id'));

        $response = $this->json($this->method, $this->endpoint, ['internship' => false]);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($regularVacancy->id, $response->json('data.0.id'));
    }
}
