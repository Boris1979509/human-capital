<?php

namespace App\Http\Resources\Institution;

use App\Http\Resources\CityResource;
use App\Http\Resources\DictionaryValueResource;
use App\Http\Resources\MediaResource;
use App\Models\City;
use App\Models\Dictionary;
use App\Models\Institution\Institution;
use App\Models\RatingCalculator;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Institution */
class InstitutionDetailedPublicResource extends JsonResource
{
    public function toArray($request): array
    {
        $ratingCalculator = new RatingCalculator(
            $this->ratings,
            $this->show_rating_employers,
            $this->show_rating_students,
            false
        );

        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'short_name' => $this->short_name,
            'city' => new CityResource(City::find($this->city_id)),
            'type' => new DictionaryValueResource(Dictionary::getById($this->inst_type_id)),
            'diploma' => new DictionaryValueResource(Dictionary::getById($this->inst_diploma_id)),
            'description' => $this->description,
            'count_curricula' => $this->curricula()->count(),
            'avatar' => new MediaResource($this->getFirstMedia('avatar')),

            'contact_description' => $this->contact_description,
            'contacts' => $this->contacts,
            'count_students' => $this->count_students,
            'avg_score' => $this->avg_score,
            'avg_salary' => $this->avg_salary,
            'rating_students' => $this->rating_students,
            'rating_employers' => $this->rating_employers,
            'rate_employment' => $this->rate_employment,

            'website' => $this->website,
            'link_vk' => $this->link_vk,
            'link_fb' => $this->link_fb,

            'inn' => $this->inn,
            'ogrn' => $this->ogrn,
            'bank' => $this->bank,
            'bank_inn' => $this->bank_inn,
            'account' => $this->account,
            'account_corr' => $this->account_corr,
            'bik' => $this->bik,
            'kpp' => $this->kpp,
            'is_favorited' => $this->isFavorited(auth()->id()),

            'rating' => $ratingCalculator->calculate(),
            'rating_user' => $this->when(
                auth()->check(),
                fn () => optional($this->ratings()->firstWhere('user_id', auth()->id()))->rating
            )
        ];
    }
}
