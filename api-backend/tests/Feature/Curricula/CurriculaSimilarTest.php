<?php

namespace Tests\Feature\Curricula;

use App\Http\Resources\Curricula\CurriculumForPublicResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurriculaSimilarTest extends TestCase
{
    use RefreshDatabase;

    private string $method = "get";

    /** @test */
    public function it_fetches_similar_curricula(): void
    {
        $curriculumType = $this->createDictionary();
        $curriculum = $this->createCurriculaForInstitution(
            $this->createInstitution(),
            1,
            ['type_id' => $curriculumType->id]
        )->first();

        $curriculaWithSameType = $this->createCurriculaForInstitution(
            $this->createInstitution(),
            3,
            ['type_id' => $curriculumType->id]
        );

        $this->createCurriculaForInstitution(
            $this->createInstitution(),
            3,
            ['type_id' => $curriculumType->id, 'is_published' => false]
        );

        $this->createCurriculaForInstitution(
            $this->createInstitution(),
            3,
            ['type_id' => $this->createDictionary()->id]
        );

        $this->assertDatabaseCount('institution_curricula', 10);

        $response = $this->json($this->method, route('curricula.similar', $curriculum->id));
        $response->assertOk();
        $this->assertEquals(
            CurriculumForPublicResource::collection($curriculaWithSameType)->response()->getData(true)['data'],
            $response->json('data')
        );
    }
}
