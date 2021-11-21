<?php

namespace Tests\Feature\Institution\Curricula;

use App\Http\Resources\Institution\CurriculumDetailedResource;
use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionCurriculum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurriculaShowTest extends TestCase
{
    use RefreshDatabase;

    private Institution $institution;
    private string $endpoint;
    private string $method = "get";
    private InstitutionCurriculum $curriculum;
    private User $institutionManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->institution = $this->createInstitution();
        $this->institutionManager = $this->createManagerOfInstitution($this->institution);
        $this->curriculum = $this->createCurriculaForInstitution($this->institution, 1)->first();
        $this->endpoint = route('institutions.curricula.admin.show', [$this->institution, $this->curriculum]);
    }

    /** @test */
    public function it_fetches_single_university_curricula(): void
    {
        $this->actingAs($this->institutionManager);
        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();
        $this->assertEquals(
            (new CurriculumDetailedResource($this->curriculum))->response()->getData(true)['data'],
            $response->json('data')
        );
    }

    /** @test */
    public function it_responds_with_404_when_curriculum_doesnt_belong_to_university(): void
    {
        $this->actingAs($this->institutionManager);
        $this->json(
            $this->method,
            route('institutions.curricula.admin.show', [$this->createInstitution()->id, $this->curriculum])
        )->assertNotFound();

        $this->json(
            $this->method,
            route(
                'institutions.curricula.admin.show',
                [
                    $this->institution,
                    $this->createCurriculaForInstitution($this->createInstitution(), 1)->first->id
                ]
            )
        )->assertNotFound();
    }

    /** @test */
    public function only_manage_of_institution_can_fetch_institution_curriculum(): void
    {
        $this->json($this->method, $this->endpoint)->assertUnauthorized();
        $this->actingAs($this->createUser())->json($this->method, $this->endpoint)->assertForbidden();
        $this->actingAs($this->institutionManager)->json($this->method, $this->endpoint)->assertOk();
    }
}
