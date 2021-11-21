<?php

namespace Tests\Feature;

use App\Models\Employer\Employer;
use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionCurriculum;
use App\Models\Journal\Content;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchIndexTest extends TestCase
{
    use RefreshDatabase;

    private Institution $institution;
    private string $endpoint;
    private string $method = "get";

    protected function setUp(): void
    {
        parent::setUp();
        $this->endpoint = route('search');
    }

    /** @test */
    public function it_searches_content(): void
    {
        $this->markTestSkipped('it requires elastic instance');
        $this->withoutExceptionHandling();
        $article = $this->createArticle(['title' => 'article foo']);
        $event = $this->createEvent(['text' => 'article foo']);
        $institution = $this->createInstitution(['full_name' => 'article foo']);
        $curriculum = $this->createCurriculaForInstitution($institution, 1, ['name' => 'article foo']);
        $employer = $this->createEmployer(null, ['name' => 'article foo']);
        $vacancy = $this->createVacancy(['name' => 'article foo']);

        Content::query()->searchable();
        Institution::query()->searchable();
        InstitutionCurriculum::query()->searchable();
        Employer::query()->searchable();
        Vacancy::query()->searchable();

        $response = $this->json($this->method, $this->endpoint, ['query' => 'article', 'filter' => ['vacancy', 'content']]);
        $response->assertOk();
        dd($response->json());
    }
}
