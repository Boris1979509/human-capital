<?php

namespace Tests\Feature\Institution\Curricula;

use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionCurriculum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurriculaDeleteTest extends TestCase
{
    use RefreshDatabase;

    private Institution $institution;
    private string $endpoint;
    private string $method = "delete";
    private InstitutionCurriculum $curriculum;
    private User $institutionManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->institution = $this->createInstitution();
        $this->curriculum = $this->createCurriculaForInstitution($this->institution, 1)->first();
        $this->institutionManager = $this->createManagerOfInstitution($this->institution);
        $this->endpoint = route('institutions.curricula.admin.delete', [$this->institution, $this->curriculum]);
    }

    /** @test */
    public function it_deletes_curriculum(): void
    {
        $this->assertDatabaseHas('institution_curricula', $this->curriculum->toArray());
        $this->actingAs($this->institutionManager);
        $response = $this->json($this->method, $this->endpoint);
        $response->assertNoContent();
        $this->assertDatabaseMissing('institution_curricula', $this->curriculum->toArray());
    }

    /** @test */
    public function it_responds_with_404_when_curriculum_doesnt_belong_to_institution(): void
    {
        $this->actingAs($this->institutionManager);
        $this->json(
            $this->method,
            route('institutions.curricula.admin.delete', [$this->createInstitution()->id, $this->curriculum])
        )->assertNotFound();

        $this->json(
            $this->method,
            route(
                'institutions.curricula.admin.delete',
                [
                    $this->institution,
                    $this->createCurriculaForInstitution($this->createInstitution(), 1)->first->id
                ]
            )
        )->assertNotFound();
    }

    /** @test */
    public function only_manager_of_institution_can_delete_curriculum(): void
    {
        $anotherUniversityManager = $this->createManagerOfInstitution($this->createInstitution());
        $regularUser = $this->createUser();

        $this->actingAs($regularUser)
            ->json($this->method, $this->endpoint)
            ->assertForbidden();

        $this->actingAs($anotherUniversityManager)
            ->json($this->method, $this->endpoint)
            ->assertForbidden();

        $this->actingAs($this->institutionManager)
            ->json($this->method, $this->endpoint)
            ->assertNoContent();
    }
}
