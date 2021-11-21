<?php

namespace Tests\Feature\Institution\Curricula;

use App\Http\Resources\Institution\CurriculumResource;
use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionCurriculum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurriculaIndexTest extends TestCase
{
    use RefreshDatabase;

    private Institution $institution;
    private string $endpoint;
    private string $method = "get";
    private User $institutionManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->institution = $this->createInstitution();
        $this->institutionManager = $this->createManagerOfInstitution($this->institution);
        $this->endpoint = route('institutions.curricula.admin.index', [$this->institution]);
    }

    /** @test */
    public function it_fetches_all_institution_curricula(): void
    {
        $curricula = $this->createCurriculaForInstitution($this->institution);
        $this->actingAs($this->institutionManager);
        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();
        $this->assertCount(InstitutionCurriculum::count(), $response->json('data'));
        $this->assertEquals(
            $curricula->sortBy('id')->pluck('id'),
            collect($response->json('data'))->sortBy('id')->pluck('id')
        );
    }

    /** @test */
    public function only_managers_of_institution_can_fetch_institution_curricula(): void
    {
        $this->json($this->method, $this->endpoint)->assertUnauthorized();
        $this->actingAs($this->createUser())->json($this->method, $this->endpoint)->assertForbidden();
        $this->actingAs($this->institutionManager)->json($this->method, $this->endpoint)->assertOk();
    }
}
