<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Search\ContentSearchResultResource;
use App\Http\Resources\Search\CurriculumSearchResultResource;
use App\Http\Resources\Search\EmployerSearchResultResource;
use App\Http\Resources\Search\InstitutionSearchResultResource;
use App\Http\Resources\Search\VacancySearchResultResource;
use App\Models\Employer\Employer;
use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionCurriculum;
use App\Models\Journal\Content;
use App\Models\Vacancy;
use ElasticScoutDriverPlus\QueryMatch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchController extends Controller
{

    public function index(Request $request)
    {
        $availableSearchTypes = collect([
            'institution' => Institution::class,
            'curriculum' => InstitutionCurriculum::class,
            'employer' => Employer::class,
            'vacancy' => Vacancy::class,
            'content' => Content::class,
        ]);

        $types = $availableSearchTypes->intersectByKeys(array_flip($request->get('filter', [])));

        if ($types->isEmpty()) {
            $types = $availableSearchTypes;
        }

        $query = $request->get('query');

        $searchQuery = $types->pop()::multiMatchSearch()
            ->autoGenerateSynonymsPhraseQuery(true)
            ->fields($this->getSearchableFields())
            ->highlight('name')
            ->highlight('title')
            ->highlight('full_name')
            ->fuzziness('AUTO')
            ->fuzzyRewrite('constant_score')
            ->fuzzyTranspositions(true)
            ->lenient(true)
            ->query($query);

        foreach ($types as $type) {
            $searchQuery->join($type);
        }

        $result = $searchQuery->execute()->matches();

        $modelResources = [
            Content::class => ContentSearchResultResource::class,
            Institution::class => InstitutionSearchResultResource::class,
            Employer::class => EmployerSearchResultResource::class,
            Vacancy::class => VacancySearchResultResource::class,
            InstitutionCurriculum::class => CurriculumSearchResultResource::class,
        ];
        $result = $result->map(function (QueryMatch $match) use ($modelResources): ?JsonResource {
            if ($match->score() < 1) {
                return null;
            }
            $modelResource = $modelResources[get_class($match->model())];
            return new $modelResource($match);
        });
        return response()->json($result);
    }

    /**
     * @return string[]
     */
    protected function getSearchableFields(): array
    {
        return [
            //institution fields
            'full_name',
            'short_name',
            'description',
            'type',
            'diploma',
            'website',
            'city',
            'contact_description',
            'entrance_test_description',
            'employees',

            //curriculum fields
            'name',
            'description',
            'direction_of_study',
            'admission_olympiad_conditions',
            'admission_additional_exam_conditions',
            'institution',
            'learning_options',
            'admission_exams',
            'result_professions',
            'competitions',
            'result_skills',
            'published_at',

            //employer fields
            'name',
            'description',
            'branch',
            'city',
            'address',
            'website',

            //vacancy fields
            'name',
            'competitions',
            'skills',
            'responsibilities',
            'requirements',
            'conditions',
            'description',
            'schedule',
            'profession',
            'experience_level',
            'employment_type',
            'city',
            'working_address',
            'employer',

            //content fields
            'title',
            'text',
            'institution',
            'address',
            'tags',
            'published_at',
            'date_start',
            'date_end',
            'participants_age',
            'target_audience',
            'speakers',
        ];
    }

}
