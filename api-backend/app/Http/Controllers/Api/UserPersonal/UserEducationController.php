<?php

namespace App\Http\Controllers\Api\UserPersonal;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserEducationRequest;
use App\Http\Resources\UserPersonalResource;
use App\Models\University;
use App\Models\User;
use App\Models\UserPersonal\UserAdditionalEducation;
use App\Models\UserPersonal\UserEducation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserEducationController extends Controller
{
    /** @var User $user */
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = Auth::user();
    }

    /**
     * @param  UserEducationRequest  $request
     * @return JsonResponse|object
     */
    public function store(UserEducationRequest $request)
    {
        // Current user
        $user = Auth::user();

        if ($request->education || $request->additional_education) {
            foreach ($request->education as $edu) {
                $userEducation = UserEducation::where(['id' => ($edu['id'] ?? null), 'user_id' => $this->user->id])
                    ->first();
                if (!$userEducation) {
                    $userEducation = UserEducation::create(['user_id' => $this->user->id]);
                }

                $userEducation->fill([
                    'university_id' => University::initialize($edu['university'])->id,
                    'edu_degree_id' => $edu['edu_degree_id'] ?? null,
                    'edu_status_id' => $edu['edu_status_id'] ?? null,
                    'edu_quality_id' => $edu['edu_quality_id'] ?? null,
                    'year_begin' => $edu['year_begin'] ?? null,
                    'year_end' => $edu['year_end'] ?? null,
                    'specialty' => $edu['specialty'] ?? null,
                    'document_number' => $edu['document_number'] ?? null,
                    'document_date' => $edu['document_date'] ?? null,
                ]);
                $userEducation->save();

                $this->updateUserEducationFiles($userEducation, $edu);
            }

            $this->syncAdditionalEducation($request->additional_education);

            return (new UserPersonalResource($this->user->fresh()))
                ->response()
                ->setStatusCode(201);
        }

        return (new UserPersonalResource($this->user->fresh()))
            ->response()
            ->setStatusCode(204);
    }

    /**
     * @throws \Exception
     */
    public function destroy(UserEducation $education)
    {
        // Удаляем образование
        $education->delete();

        return (new UserPersonalResource($this->user->fresh()))
            ->response()
            ->setStatusCode(200);
    }

    private function updateUserEducationFiles(UserEducation $userEducation, array $userEducationData): void
    {
        $this->syncMediaWithModel($userEducation, $userEducationData['files'], 'education');
    }

    private function syncAdditionalEducation($additionalEducation): void
    {
        // сначала удалим все образования, которые не пришли в запросе
        $addedEduIds = collect($additionalEducation)
            ->pluck('id')
            ->filter(fn (?int $edu) => $edu !== null)
            ->toArray();
        UserAdditionalEducation::where('user_id', $this->user->id)->whereNotIn('id', $addedEduIds)->delete();

        // обновим образования, которые пришли с айдишником
        $eduToUpdate = collect($additionalEducation)->filter(fn ($edu) => isset($edu['id']));
        $eduToUpdate->each(
            function (array $edu) {
                UserAdditionalEducation::where('id', $edu['id'])->update($edu);
            }
        );

        // добавим новые образования, которые пришли без айдишника
        collect($additionalEducation)->each(function ($eduData) {
            if (!isset($eduData['id'])) {
                $this->user->additionalEducation()->create($eduData);
            }
        });
    }
}
