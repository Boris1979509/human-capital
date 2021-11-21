<?php

namespace App\Http\Resources\Employer;

use App\Http\Resources\CityResource;
use App\Http\Resources\DictionaryValueResource;
use App\Http\Resources\MediaResource;
use App\Models\City;
use App\Models\Dictionary;
use App\Models\Employer\Employer;
use App\Models\RatingCalculator;
use App\Models\Subscription;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Employer */
class EmployerDetailedPublicResource extends JsonResource
{
    public function toArray($request): array
    {
        $ratingCalculator = new RatingCalculator(
            $this->ratings,
            true,
            false,
            true
        );

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'branch' => new DictionaryValueResource(Dictionary::getById($this->branch_id)),
            'avatar' => new MediaResource($this->getFirstMedia('avatar')),
            'cover' => new MediaResource($this->getFirstMedia('cover')),
            'images' => MediaResource::collection($this->getMedia('images')),
            'city' => new CityResource(City::find($this->city_id)),
            'is_internship' => $this->is_internship,
            'address' => $this->address,
            'coords' => $this->coords,
            'website' => $this->website,
            'show_contacts' => $this->show_contacts,
            'contacts' => $this->contacts,
            'count_employees' => $this->count_employees,
            'show_vacancies_count' => $this->show_vacancies_count,
            'vacancies_count' => $this->vacancies()->count(),
            'internships' => $this->getInternshipsCount(),

            'rating' => $ratingCalculator->calculate(),
            'rating_user' => $this->when(
                auth()->check(),
                fn () => optional($this->ratings()->firstWhere('user_id', auth()->id()))->rating
            ),

            'is_favorited' => $this->isFavorited(auth()->id()),

            'subscriptions' => $this->when(auth()->check(), function () {
                return [
                    'journal' => $this->subscriptions()
                        ->where('user_id', auth()->id())
                        ->where('type', Subscription::TYPE_JOURNAL)
                        ->exists(),
                    'vacancies' => $this->subscriptions()
                        ->where('user_id', auth()->id())
                        ->where('type', Subscription::TYPE_VACANCIES)
                        ->exists(),
                ];
            })
        ];
    }
}
