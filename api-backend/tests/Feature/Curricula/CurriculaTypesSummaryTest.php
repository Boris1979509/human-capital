<?php

namespace Tests\Feature\Curricula;

use App\Models\Dictionary;
use App\Models\Institution\Institution;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurriculaTypesSummaryTest extends TestCase
{
    use RefreshDatabase;

    private Institution $institution;
    private string $endpoint;
    private string $method = "get";
    private Dictionary $instType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->instType = Dictionary::create(['type' => Dictionary::TYPE_INST_TYPE, 'option' => 'inst type']);
        $this->institution = $this->createInstitution(['inst_type_id' => $this->instType->id]);
        $this->endpoint = route('curricula.types.summary');
    }

    /** @test */
    public function it_fetches_correct_summary_grouped_by_inst_type(): void
    {
        $this->withoutExceptionHandling();
        $typeAName = 'A';
        $curriculaTypeA = Dictionary::create(['type' => Dictionary::TYPE_INST_PROGRAM, 'option' => $typeAName]);
        $typeACurriculaCount = 3;
        $typeACurricula = $this->createCurriculaForInstitution(
            $this->institution,
            $typeACurriculaCount,
            ['type_id' => $curriculaTypeA->id]
        );

        $typeBName = 'B';
        $curriculaTypeB = Dictionary::create(['type' => Dictionary::TYPE_INST_PROGRAM, 'option' => $typeBName]);
        $typeBCurriculaCount = 5;
        $this->createCurriculaForInstitution(
            $this->institution,
            $typeBCurriculaCount,
            ['type_id' => $curriculaTypeB->id]
        );

        //программы, которые не войдут в выборку
        $otherInstitutionType = Dictionary::create([
            'type' => Dictionary::TYPE_INST_TYPE,
            'option' => 'some other inst type'
        ]);
        $this->createCurriculaForInstitution(
            $this->createInstitution(['inst_type_id' => $otherInstitutionType]),
            5,
            ['type_id' => $curriculaTypeB->id]
        );

        $response = $this->json($this->method, $this->endpoint, ['inst_type' => $this->instType->id]);
        $response->assertOk();
        $data = $response->json('data');

        $this->assertEquals($data[0]['id'], $curriculaTypeB->id);
        $this->assertEquals($data[0]['name'], $typeBName);
        $this->assertEquals($data[0]['count'], $typeBCurriculaCount);

        $this->assertEquals($data[1]['id'], $curriculaTypeA->id);
        $this->assertEquals($data[1]['name'], $typeAName);
        $this->assertEquals($data[1]['count'], $typeACurriculaCount);
    }
}
