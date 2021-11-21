<?php

namespace Tests\Feature\Curricula;

use App\Models\Dictionary;
use App\Models\Institution\Institution;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurriculaSummaryTest extends TestCase
{
    use RefreshDatabase;

    private Institution $institution;
    private string $endpoint;
    private string $method = "get";
    private Dictionary $instType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->institution = $this->createInstitution();
        $this->endpoint = route('curricula.summary');
    }

    /** @test */
    public function it_fetches_summary(): void
    {
        $minPrice = 100;
        $maxPrice = 500;

        $this->createCurriculaForInstitution(
            $this->institution,
            1,
            ['learning_options' => [['cost' => $minPrice], ['cost' => 200]]]
        );
        $this->createCurriculaForInstitution(
            $this->institution,
            1,
            ['learning_options' => [['cost' => 200], ['cost' => 300]]]
        );
        $this->createCurriculaForInstitution(
            $this->institution,
            1,
            ['learning_options' => [['cost' => $maxPrice], ['cost' => 200]]]
        );
        $this->createCurriculaForInstitution(
            $this->institution,
            1,
            ['learning_options' => [['cost' => $maxPrice], ['cost' => $minPrice]]]
        );

        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();
        $this->assertEquals($minPrice, $response->json('MIN_PRICE'));
        $this->assertEquals($maxPrice, $response->json('MAX_PRICE'));
        $this->assertEquals(4, $response->json('COUNT'));
    }

    /** @test */
    public function it_filters_summary_by_filter(): void
    {
        $minPriceForEntrant = 100;
        $maxPriceForEntrant = 500;

        $forEntrantDictionaryValue = $this->createDictionary(config('app.dictionaries.for_entrant'));
        $forChildrenDictionaryValue = $this->createDictionary(config('app.dictionaries.for_children'));


        $this->createCurriculaForInstitution(
            $this->institution,
            1,
            [
                'learning_options' => [
                    ['cost' => $minPriceForEntrant, 'auditory' => $forEntrantDictionaryValue->id],
                    ['cost' => 200, 'auditory' => $forEntrantDictionaryValue->id]
                ]
            ]
        );
        $this->createCurriculaForInstitution(
            $this->institution,
            1,
            [
                'learning_options' => [
                    ['cost' => $maxPriceForEntrant, 'auditory' => $forEntrantDictionaryValue->id],
                    ['cost' => 300, 'auditory' => $forEntrantDictionaryValue->id]
                ]
            ]
        );

        $this->createCurriculaForInstitution(
            $this->institution,
            1,
            [
                'learning_options' => [
                    ['cost' => 10, 'auditory' => $forChildrenDictionaryValue->id],
                    ['cost' => 200, 'auditory' => $forChildrenDictionaryValue->id]
                ]
            ]
        );
        $this->createCurriculaForInstitution(
            $this->institution,
            1,
            [
                'learning_options' => [
                    ['cost' => 5000, 'auditory' => $forChildrenDictionaryValue->id],
                    ['cost' => 300, 'auditory' => $forChildrenDictionaryValue->id]
                ]
            ]
        );

        $response = $this->json($this->method, $this->endpoint, ['filter' => 'for_entrant']);
        $response->assertOk();
        $this->assertEquals($minPriceForEntrant, $response->json('MIN_PRICE'));
        $this->assertEquals($maxPriceForEntrant, $response->json('MAX_PRICE'));
        $this->assertEquals(4, $response->json('COUNT'));
    }

    /** @test */
    public function it_filters_summary_by_institution_id(): void
    {
        $minPrice = 100;
        $maxPrice = 500;

        $this->createCurriculaForInstitution(
            $this->institution,
            1,
            [
                'learning_options' => [
                    ['cost' => $maxPrice],
                    ['cost' => $minPrice],
                    ['cost' => 200]
                ]
            ]
        );

        $this->createCurriculaForInstitution(
            $this->institution,
            1,
            [
                'learning_options' => [
                    ['cost' => $maxPrice - 100],
                    ['cost' => $minPrice + 100],
                    ['cost' => 200]
                ]
            ]
        );
        $this->createCurriculaForInstitution(
            $this->createInstitution(),
            1,
            [
                'learning_options' => [
                    ['cost' => $maxPrice + 100],
                    ['cost' => $minPrice - 100],
                    ['cost' => 300]
                ]
            ]
        );

        $response = $this->json($this->method, $this->endpoint, ['institution_id' => $this->institution->id]);
        $response->assertOk();
        $this->assertEquals($minPrice, $response->json('MIN_PRICE'));
        $this->assertEquals($maxPrice, $response->json('MAX_PRICE'));
        $this->assertEquals(2, $response->json('COUNT'));
    }

    /** @test */
    public function it_aggregates_summary_only_by_published_curricula(): void
    {
        $minPrice = 100;
        $maxPrice = 500;

        $this->createCurriculaForInstitution(
            $this->institution,
            1,
            [
                'learning_options' => [
                    ['cost' => $maxPrice],
                    ['cost' => $minPrice],
                    ['cost' => 200]
                ]
            ]
        );

        $this->createCurriculaForInstitution(
            $this->institution,
            1,
            [
                'learning_options' => [
                    ['cost' => $maxPrice - 100],
                    ['cost' => $minPrice + 100],
                    ['cost' => 200]
                ]
            ]
        );
        $this->createCurriculaForInstitution(
            $this->institution,
            1,
            [
                'learning_options' => [
                    ['cost' => $maxPrice + 100],
                    ['cost' => $minPrice - 100],
                    ['cost' => 300]
                ],
                'is_published' => false
            ]
        );

        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();
        $this->assertEquals($minPrice, $response->json('MIN_PRICE'));
        $this->assertEquals($maxPrice, $response->json('MAX_PRICE'));
        $this->assertEquals(2, $response->json('COUNT'));
    }
}
