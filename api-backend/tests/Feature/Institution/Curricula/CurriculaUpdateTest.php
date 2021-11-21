<?php

namespace Tests\Feature\Institution\Curricula;

use App\Http\Resources\Institution\CurriculumResource;
use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionCurriculum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurriculaUpdateTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "put";
    private Institution $institution;
    private InstitutionCurriculum $curriculum;
    private User $institutionManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->institution = $this->createInstitution();
        $this->curriculum = $this->createCurriculaForInstitution($this->institution, 1)->first();
        $this->institutionManager = $this->createManagerOfInstitution($this->institution);
        $this->endpoint = route('institutions.curricula.admin.update', [$this->institution, $this->curriculum]);
    }

    /** @test */
    public function it_creates_curriculum(): void
    {
        $this->assertDatabaseCount('institution_curricula', 1);
        $this->assertDatabaseHas('institution_curricula', $this->curriculum->toArray());

        $this->actingAs($this->institutionManager);
        $response = $this->json(
            $this->method,
            $this->endpoint,
            [
                'name' => 'new name',
                'direction_of_study' => 'dos',
                'description' => 'desc',
                'competitions' => ['competition']
            ]
        );
        $response->assertOk();
        $this->assertDatabaseCount('institution_curricula', 1);
        $this->assertDatabaseMissing('institution_curricula', $this->curriculum->toArray());
        $this->assertDatabaseHas('institution_curricula', $this->curriculum->fresh()->toArray());
        $this->assertEquals($this->institution->id, InstitutionCurriculum::first()->institution_id);
        $this->assertEquals(
            (new CurriculumResource(InstitutionCurriculum::first()))->response()->getData(true)['data'],
            $response->json()
        );
    }

    /** @test */
    public function only_manager_of_institution_can_update_curriculum(): void
    {
        $curriculumData = InstitutionCurriculum::factory()->makeOne()->toArray();
        $curriculumData['competitions'] = ['1', '2'];

        $anotherUniversityManager = $this->createManagerOfInstitution($this->createInstitution());
        $regularUser = $this->createUser();

        $this->actingAs($regularUser)
            ->json($this->method, $this->endpoint, $curriculumData)
            ->assertForbidden();

        $this->actingAs($anotherUniversityManager)
            ->json($this->method, $this->endpoint, $curriculumData)
            ->assertForbidden();

        $this->actingAs($this->institutionManager)
            ->json($this->method, $this->endpoint, $curriculumData)
            ->assertOk();
    }

    /** @test */
    public function it_sets_published_at_date(): void
    {
        $this->curriculum->update(['published_at' => null, 'is_published' => false]);
        $this->curriculum = $this->curriculum->fresh();

        $this->assertNull($this->curriculum->published_at);

        $this->actingAs($this->institutionManager);
        $response = $this->json($this->method, $this->endpoint, [
            'is_published' => true,
            'name' => $this->curriculum->name,
            'direction_of_study' => 'dos',
            'description' => 'desc',
            'competitions' => ['competition']
        ]);
        $response->assertOk();

        $this->assertNotNull($this->curriculum->fresh()->published_at);
    }

    /** @test */
    public function it_doesnt_update_published_at_when_curriculum_is_already_published(): void
    {
        $publishedAt = now();
        $this->curriculum->update(['published_at' => $publishedAt, 'is_published' => true]);

        $this->actingAs($this->institutionManager);
        $response = $this->json($this->method, $this->endpoint, [
            'is_published' => true,
            'name' => $this->curriculum->name,
            'direction_of_study' => 'dos',
            'description' => 'desc',
            'competitions' => ['competition']
        ]);
        $response->assertOk();

        $this->assertEquals($publishedAt->setMicroseconds(0), $this->curriculum->fresh()->published_at);
    }
}
