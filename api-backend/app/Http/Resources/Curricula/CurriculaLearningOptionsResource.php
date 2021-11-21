<?php

namespace App\Http\Resources\Curricula;

use App\Http\Resources\DictionaryValueResource;
use App\Models\Dictionary;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CurriculaLearningOptionsResource
{
    protected Collection $learningOptions;

    public function __construct(?array $learningOptions)
    {
        $this->learningOptions = collect($learningOptions);
    }

    public function toArray(Request $request): array
    {
        $filters = $this->collectFiltersToApplyFromRequest($request);

        $learningOptions = $this->learningOptions;

        foreach ($filters as $filter) {
            $learningOptions = $learningOptions->filter($filter);
        }

        $learningOptions = $learningOptions->map(function ($learningOption) {
            if (isset($learningOption['edu_form'])) {
                $eduFormDictionary = Dictionary::getById($learningOption['edu_form']);
                $learningOption['edu_form'] = new DictionaryValueResource($eduFormDictionary);
            }
            if (isset($learningOption['auditory'])) {
                $auditoryDictionary = Dictionary::getById($learningOption['auditory']);
                $learningOption['auditory'] = new DictionaryValueResource($auditoryDictionary);
            }

            return $learningOption;
        });

        return $learningOptions->toArray();
    }

    private function collectFiltersToApplyFromRequest(Request $request): array
    {
        $filtersToApply = [];

        if ($request->has('edu_form')) {
            $filtersToApply[] =
                static fn (array $learningOption): bool => (int) $learningOption['edu_form'] === (int) $request->get('edu_form');
        }

        if ($request->has('filter')) {
            $auditory = $request->get('filter');
            $auditoryId = config("app.dictionaries.$auditory");
            $filtersToApply[] =
                static fn (array $learningOption): bool => (int) $learningOption['auditory'] === (int) $auditoryId;
        }

        if ($request->has('max_cost')) {
            $filtersToApply[] =
                static fn (array $learningOption): bool => (int) $learningOption['cost'] <= (int) $request->get('max_cost');
        }
        return $filtersToApply;
    }
}
