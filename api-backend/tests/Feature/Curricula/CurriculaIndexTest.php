<?php

namespace Tests\Feature\Curricula;

use App\Http\Resources\Curricula\CurriculumForPublicResource;
use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionCurriculum;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurriculaIndexTest extends TestCase
{
    use RefreshDatabase;

    private Institution $institution;
    private string $endpoint;
    private string $method = "get";

    protected function setUp(): void
    {
        parent::setUp();
        $this->institution = $this->createInstitution();
        $this->endpoint = route('curricula.index', [$this->institution]);
    }

    /** @test */
    public function it_fetches_all_institution_curricula(): void
    {
        $this->withoutExceptionHandling();
        $curricula = $this->createCurriculaForInstitution($this->institution, 20);
        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();
        $this->assertCount(InstitutionCurriculum::count(), $response->json('data'));
        $expectedCurricula = $curricula->pluck('id')->sort();
        $fetchedCurricula = collect($response->json('data'))->pluck('id')->sort()->toArray();
        sort($fetchedCurricula);
        $this->assertEquals(
            $expectedCurricula->toArray(),
            $fetchedCurricula
        );
    }

    /** @test */
    public function it_fetches_only_published_curricula(): void
    {
        $publishedCurriculum = $this->createCurriculaForInstitution(
            $this->institution,
            1,
            ['is_published' => true]
        )->first();
        $this->createCurriculaForInstitution($this->institution, 1, ['is_published' => false]);

        $this->assertDatabaseCount('institution_curricula', 2);

        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals(
            $publishedCurriculum->id,
            $response->json('data.0.id')
        );
    }

    /** @test */
    public function it_paginates_result_request(): void
    {
        $this->createCurriculaForInstitution($this->institution, 20);
        $response = $this->json($this->method, $this->endpoint, ['page' => 1]);
        $response->assertOk();
        $this->assertCount(15, $response->json('data'));

        $response = $this->json($this->method, $this->endpoint, ['per_page' => 6]);
        $response->assertOk();
        $this->assertCount(6, $response->json('data'));
    }

    /** @test */
    public function it_orders_curricula_by_published_at_field(): void
    {
        $second = $this->createCurriculaForInstitution($this->institution, 1, ['is_published' => true])->first();
        $first = $this->createCurriculaForInstitution($this->institution, 1, ['is_published' => true])->first();
        $second->update(['published_at' => Carbon::yesterday()]);
        $first->update(['published_at' => Carbon::tomorrow()]);

        $this->assertDatabaseCount('institution_curricula', 2);

        $response = $this->json($this->method, $this->endpoint, ['order_by' => '-published_at']);
        $response->assertOk();

        $fetchedData = $response->json('data');
        $this->assertCount(2, $fetchedData);
        $this->assertEquals($first->id, $fetchedData[0]['id']);
        $this->assertEquals($second->id, $fetchedData[1]['id']);
    }

    /** @test */
    public function it_filters_curricula_by_filter_abstraction(): void
    {
        $forEntrantDictionaryValue = $this->createDictionary(config('app.dictionaries.for_entrant'));
        $curriculumForEntrant = $this->createCurriculaForInstitution(
            $this->institution,
            1,
            [
                'learning_options' => [
                    ['auditory' => $forEntrantDictionaryValue->id], ['auditory' => 9]
                ]
            ]
        )->first();

        $forChildrenDictionaryValue = $this->createDictionary(config('app.dictionaries.for_children'));
        $curriculumForChildren = $this->createCurriculaForInstitution(
            $this->institution,
            1,
            ['learning_options' => [['auditory' => $forChildrenDictionaryValue->id], ['auditory' => 9]]]
        )->first();

        $forAdultDictionaryValue = $this->createDictionary(config('app.dictionaries.for_adult'));
        $curriculumForAdult = $this->createCurriculaForInstitution(
            $this->institution,
            1,
            ['learning_options' => [['auditory' => $forAdultDictionaryValue->id], ['auditory' => 9]]]
        )->first();

        $curriculumForAll = $this->createCurriculaForInstitution(
            $this->institution,
            1,
            [
                'learning_options' => [
                    ['auditory' => $forEntrantDictionaryValue->id],
                    ['auditory' => $forAdultDictionaryValue->id],
                    ['auditory' => $forChildrenDictionaryValue->id],
                    ['auditory' => 9]
                ]
            ]
        )->first();

        $this->assertDatabaseCount('institution_curricula', 4);

        $response = $this->json($this->method, $this->endpoint, ['filter' => 'for_entrant']);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals(
            collect([$curriculumForEntrant, $curriculumForAll])->sortBy('id')->pluck('id'),
            collect($response->json('data'))->sortBy('id')->pluck('id')
        );
        $this->assertCount(1, $response->json('data.0.learning_options'));
        $this->assertEquals($forEntrantDictionaryValue->id, $response->json('data.0.learning_options.0.auditory.id'));
        $this->assertCount(1, $response->json('data.1.learning_options'));
        $this->assertEquals($forEntrantDictionaryValue->id, $response->json('data.1.learning_options.0.auditory.id'));

        $response = $this->json($this->method, $this->endpoint, ['filter' => 'for_children']);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals(
            collect([$curriculumForChildren, $curriculumForAll])->sortBy('id')->pluck('id'),
            collect($response->json('data'))->sortBy('id')->pluck('id')
        );
        $this->assertCount(1, $response->json('data.0.learning_options'));
        $this->assertEquals($forChildrenDictionaryValue->id, $response->json('data.0.learning_options.0.auditory.id'));
        $this->assertCount(1, $response->json('data.1.learning_options'));
        $this->assertEquals($forChildrenDictionaryValue->id, $response->json('data.1.learning_options.0.auditory.id'));

        $response = $this->json($this->method, $this->endpoint, ['filter' => 'for_adult']);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals(
            collect([$curriculumForAdult, $curriculumForAll])->pluck('id'),
            collect($response->json('data'))->pluck('id')
        );
        $this->assertCount(1, $response->json('data.0.learning_options'));
        $this->assertEquals($forAdultDictionaryValue->id, $response->json('data.0.learning_options.0.auditory.id'));
        $this->assertCount(1, $response->json('data.1.learning_options'));
        $this->assertEquals($forAdultDictionaryValue->id, $response->json('data.1.learning_options.0.auditory.id'));

        // проверка на то, что эта фильтрация работает с остальными фильтрами
        $response = $this->json(
            $this->method,
            $this->endpoint,
            [
                'filter' => 'for_adult',
                'institution_id' => $this->institution->id,
                'limit' => 15
            ]
        );
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals(
            collect([$curriculumForAdult, $curriculumForAll])->pluck('id'),
            collect($response->json('data'))->pluck('id')
        );
    }

    /** @test */
    public function it_filters_curricula_by_edu_form(): void
    {
        $simpleEduForm = $this->createDictionary();
        $curriculumWithSimpleEduForm = $this->createCurriculaForInstitution(
            $this->institution,
            1,
            ['learning_options' => [['edu_form' => $simpleEduForm->id], ['edu_form' => 9]]]
        )->first();

        $complexEduForm = $this->createDictionary();
        $curriculumWithComplexEduForm = $this->createCurriculaForInstitution(
            $this->institution,
            1,
            ['learning_options' => [['edu_form' => $complexEduForm->id], ['edu_form' => 9]]]
        )->first();

        $curriculumWithSimpleAndComplexEduForm = $this->createCurriculaForInstitution(
            $this->institution,
            1,
            [
                'learning_options' => [
                    ['edu_form' => $simpleEduForm->id], ['edu_form' => $complexEduForm->id], ['edu_form' => 9]
                ]
            ]
        )->first();

        $this->assertDatabaseCount('institution_curricula', 3);

        $response = $this->json($this->method, $this->endpoint, ['edu_form' => $simpleEduForm->id]);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals(
            collect([$curriculumWithSimpleEduForm, $curriculumWithSimpleAndComplexEduForm])->pluck('id'),
            collect($response->json('data'))->pluck('id')
        );
        $this->assertCount(1, $response->json('data.0.learning_options'));
        $this->assertEquals($simpleEduForm->id, $response->json('data.0.learning_options.0.edu_form.id'));
        $this->assertCount(1, $response->json('data.1.learning_options'));
        $this->assertEquals($simpleEduForm->id, $response->json('data.1.learning_options.0.edu_form.id'));

        $response = $this->json($this->method, $this->endpoint, ['edu_form' => $complexEduForm->id]);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals(
            collect([$curriculumWithComplexEduForm, $curriculumWithSimpleAndComplexEduForm])->pluck('id'),
            collect($response->json('data'))->pluck('id')
        );
        $this->assertCount(1, $response->json('data.0.learning_options'));
        $this->assertEquals($complexEduForm->id, $response->json('data.0.learning_options.0.edu_form.id'));
        $this->assertCount(1, $response->json('data.1.learning_options'));
        $this->assertEquals($complexEduForm->id, $response->json('data.1.learning_options.0.edu_form.id'));
    }

    /** @test */
    public function it_filters_curricula_learning_options_correctly(): void
    {
        $this->withoutExceptionHandling();
        $this->createDictionary(config('app.dictionaries.for_entrant'));
        $this->createDictionary(48);
        $data = json_decode(
            '[{"cost": 943, "auditory": 45, "edu_form": 51}, {"cost": 608, "auditory": 47, "edu_form": 48}, {"cost": 971, "auditory": 47, "edu_form": 49}]',
            true
        );
        $this->createCurriculaForInstitution(
            $this->institution,
            1,
            [
                'learning_options' => $data
            ]
        )->first();

        $response = $this->json($this->method, $this->endpoint, [
            'edu_form' => '48',
            'order_by' => '-published_at',
            'limit' => '6',
            'filter' => 'for_entrant',
            'max_cost' => '999000'
        ]);
        $response->assertOk();
        $this->assertCount(0, $response->json('data'));
    }

    /** @test */
    public function it_substitutes_auditory_and_edu_form(): void
    {
        $auditory = $this->createDictionary(null, ['option' => 'for_entrant']);
        $simpleEduForm = $this->createDictionary(null, ['option' => 'simple']);
        $this->createCurriculaForInstitution(
            $this->institution,
            1,
            [
                'learning_options' => [
                    [
                        'cost' => 100,
                        'auditory' => $auditory->id,
                        'edu_form' => $simpleEduForm->id,
                    ]
                ]
            ]
        )->first();

        $response = $this->json($this->method, $this->endpoint, ['max_cost' => 400]);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));

        $this->assertCount(1, $response->json('data.0.learning_options'));
        $this->assertEquals($simpleEduForm->id, $response->json('data.0.learning_options.0.edu_form.id'));
        $this->assertEquals($simpleEduForm->option, $response->json('data.0.learning_options.0.edu_form.name'));
        $this->assertEquals($auditory->id, $response->json('data.0.learning_options.0.auditory.id'));
        $this->assertEquals($auditory->option, $response->json('data.0.learning_options.0.auditory.name'));
    }

    /** @test */
    public function it_filters_curricula_by_institution_id(): void
    {
        $curriculum = $this->createCurriculaForInstitution($this->institution, 1)->first();
        $this->createCurriculaForInstitution($this->createInstitution(), 1);

        $this->assertDatabaseCount('institution_curricula', 2);
        $response = $this->json($this->method, $this->endpoint, ['institution_id' => $this->institution->id]);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($curriculum->id, $response->json('data.0.id'));
    }

    /** @test */
    public function it_filters_curricula_by_favorite(): void
    {
        $user = $this->createUser();
        $favoritedPublicCurriculum = $this->createCurriculaForInstitution($this->institution, 1)->first();
        $user->addFavorite($favoritedPublicCurriculum);
        $favoritedUnpublishedCurriculum = $this->createCurriculaForInstitution(
            $this->institution,
            1,
            ['is_published' => false]
        )->first();
        $user->addFavorite($favoritedUnpublishedCurriculum);
        $notFavoritedCurriculum = $this->createCurriculaForInstitution($this->institution, 1)->first();
        $this->createUser()->addFavorite($notFavoritedCurriculum);


        $this->assertDatabaseCount('institution_curricula', 3);
        $response = $this->json($this->method, $this->endpoint, ['favorited' => true]);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));

        $response = $this->actingAs($user)->json($this->method, $this->endpoint, ['favorited' => true]);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($favoritedPublicCurriculum->id, $response->json('data.0.id'));
    }

    /** @test */
    public function it_filters_curricula_by_max_cost(): void
    {
        $curriculumWith100Cost = $this->createCurriculaForInstitution(
            $this->institution,
            1,
            ['learning_options' => [['cost' => 100], ['cost' => 9999]]]
        )->first();

        $curriculumWith200Cost = $this->createCurriculaForInstitution(
            $this->institution,
            1,
            ['learning_options' => [['cost' => 200], ['cost' => 9999]]]
        )->first();

        $curriculumWith100and200Cost = $this->createCurriculaForInstitution(
            $this->institution,
            1,
            ['learning_options' => [['cost' => 200], ['cost' => 100], ['cost' => 9999]]]
        )->first();

        $this->assertDatabaseCount('institution_curricula', 3);

        $response = $this->json($this->method, $this->endpoint, ['max_cost' => 100]);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals(
            collect([$curriculumWith100Cost, $curriculumWith100and200Cost])->sortBy('id')->pluck('id'),
            collect($response->json('data'))->sortBy('id')->pluck('id')
        );
        $this->assertCount(1, $response->json('data.0.learning_options'));
        $this->assertLessThanOrEqual(100, $response->json('data.0.learning_options.0.cost'));
        $this->assertCount(1, $response->json('data.1.learning_options'));
        $this->assertLessThanOrEqual(100, $response->json('data.1.learning_options.0.cost'));

        $response = $this->json($this->method, $this->endpoint, ['max_cost' => 150]);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals(
            collect([$curriculumWith100Cost, $curriculumWith100and200Cost])->sortBy('id')->pluck('id'),
            collect($response->json('data'))->sortBy('id')->pluck('id')
        );
        $this->assertCount(1, $response->json('data.0.learning_options'));
        $this->assertLessThanOrEqual(150, $response->json('data.0.learning_options.0.cost'));
        $this->assertCount(1, $response->json('data.1.learning_options'));
        $this->assertLessThanOrEqual(150, $response->json('data.1.learning_options.0.cost'));

        $response = $this->json($this->method, $this->endpoint, ['max_cost' => 200, 'order_by' => '-published_at']);
        $response->assertOk();
        $this->assertCount(3, $response->json('data'));
        $this->assertEquals(
            collect([
                $curriculumWith100Cost, $curriculumWith200Cost, $curriculumWith100and200Cost
            ])->sortBy('id')->pluck('id'),
            collect($response->json('data'))->sortBy('id')->pluck('id')
        );
        foreach ($response->json('data') as $curriculum) {
            if ($curriculum['id'] === $curriculumWith100Cost->id || $curriculum['id'] === $curriculumWith200Cost->id) {
                $this->assertCount(1, $curriculum['learning_options']);
                $this->assertLessThanOrEqual(200, $curriculum['learning_options'][0]['cost']);
            }
            if ($curriculum['id'] === $curriculumWith100and200Cost->id) {
                $this->assertCount(2, $curriculum['learning_options']);
                $this->assertLessThanOrEqual(200, $curriculum['learning_options'][0]['cost']);
                $this->assertLessThanOrEqual(200, $curriculum['learning_options'][1]['cost']);
            }
        }

        $response = $this->json($this->method, $this->endpoint, ['max_cost' => 300]);
        $response->assertOk();
        $this->assertCount(3, $response->json('data'));
        $this->assertEquals(
            collect([
                $curriculumWith100Cost, $curriculumWith200Cost, $curriculumWith100and200Cost
            ])->sortBy('id')->pluck('id'),
            collect($response->json('data'))->sortBy('id')->pluck('id')
        );
        foreach ($response->json('data') as $curriculum) {
            if ($curriculum['id'] === $curriculumWith100Cost->id || $curriculum['id'] === $curriculumWith200Cost->id) {
                $this->assertCount(1, $curriculum['learning_options']);
                $this->assertLessThanOrEqual(200, $curriculum['learning_options'][0]['cost']);
            }
            if ($curriculum['id'] === $curriculumWith100and200Cost->id) {
                $this->assertCount(2, $curriculum['learning_options']);
                $this->assertLessThanOrEqual(200, $curriculum['learning_options'][0]['cost']);
                $this->assertLessThanOrEqual(200, $curriculum['learning_options'][1]['cost']);
            }
        }

        $response = $this->json($this->method, $this->endpoint, ['max_cost' => 50]);
        $response->assertOk();
        $this->assertCount(0, $response->json('data'));
    }
}
