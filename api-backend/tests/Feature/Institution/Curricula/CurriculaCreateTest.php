<?php

namespace Tests\Feature\Institution\Curricula;

use App\Http\Resources\Institution\CurriculumResource;
use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionCurriculum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CurriculaCreateTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "post";
    private Institution $institution;
    private User $institutionManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->institution = $this->createInstitution();
        $this->endpoint = route('institutions.curricula.admin.create', [$this->institution]);
        $this->institutionManager = $this->createManagerOfInstitution($this->institution);
    }

    /** @test */
    public function it_creates_curriculum(): void
    {
        $curriculumData = InstitutionCurriculum::factory()->make(['institution_id' => $this->institution->id])->toArray();
        $curriculumData['competitions'] = ['1', '2'];
        $this->assertDatabaseCount('institution_curricula', 0);
        $this->actingAs($this->institutionManager);
        $response = $this->json($this->method, $this->endpoint, $curriculumData);
        $response->assertCreated();
        $this->assertDatabaseCount('institution_curricula', 1);
        unset($curriculumData['competitions']);
        $this->assertDatabaseHas('institution_curricula', $curriculumData);
        $this->assertEquals($this->institution->id, InstitutionCurriculum::first()->institution_id);
        $this->assertEquals(
            (new CurriculumResource(InstitutionCurriculum::first()))->response()->getData(true)['data'],
            $response->json()
        );
    }

    /** @test */
    public function only_manager_of_institution_can_create_curriculum(): void
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
            ->assertCreated();
    }

    /** @test */
    public function it_sets_published_at_field(): void
    {
        $this->actingAs($this->institutionManager);
        $curriculumData = InstitutionCurriculum::factory()->make(['is_published' => true])->toArray();
        $curriculumData['competitions'] = ['1', '2'];

        $response = $this->json($this->method, $this->endpoint, $curriculumData);
        $response->assertCreated();

        $this->assertDatabaseCount('institution_curricula', 1);
        $createdCurricula = InstitutionCurriculum::first();
        $this->assertNotNull($createdCurricula->published_at);
    }

    /** @test */
    public function it_validates_admission_exams_field(): void
    {
        $this->actingAs($this->institutionManager);
        $curriculumData = InstitutionCurriculum::factory()->make()->toArray();

        $unknownSubjectId = 999;
        $this->assertDatabaseMissing('dictionaries', ['id' => $unknownSubjectId]);

        $curriculumData['admission_exams'] = [
            [
                'subject' => 'subject',
                'points' => '123'
            ],
            [
                'subject' => $unknownSubjectId,
                'points' => '322'
            ]
        ];

        $response = $this->json($this->method, $this->endpoint, $curriculumData);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('admission_exams.1.subject');
        $response->assertJsonValidationErrors('admission_exams.0.subject');
    }

    /** @test */
    public function it_validates_learning_options_field(): void
    {
        $this->actingAs($this->institutionManager);
        $curriculumData = InstitutionCurriculum::factory()->make()->toArray();

        $unknownDictionaryId = 999;
        $this->assertDatabaseMissing('dictionaries', ['id' => $unknownDictionaryId]);
        $existingDictionaryId = $this->createDictionary()->id;

        $curriculumData['learning_options'] = [
            [
                'auditory' => $unknownDictionaryId,
                'edu_form' => $existingDictionaryId,
                'cost' => '123'
            ],
            [
                'auditory' => $existingDictionaryId,
                'edu_form' => $unknownDictionaryId,
                'cost' => '123'
            ],
            [
                'auditory' => $existingDictionaryId,
                'edu_form' => $existingDictionaryId,
                'cost' => 'a lot'
            ],
        ];

        $response = $this->json($this->method, $this->endpoint, $curriculumData);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('learning_options.0.auditory');
        $response->assertJsonValidationErrors('learning_options.1.edu_form');
        $response->assertJsonValidationErrors('learning_options.2.cost');
    }
}
