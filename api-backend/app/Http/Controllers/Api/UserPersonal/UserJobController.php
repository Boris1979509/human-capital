<?php

namespace App\Http\Controllers\Api\UserPersonal;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserJobRequest;
use App\Http\Resources\UserPersonalResource;
use App\Models\User;
use App\Models\UserPersonal\UserJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserJobController extends Controller
{
    /** @var User $user */
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = Auth::user();
    }

    /**
     * @param UserJobRequest $request
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function store(UserJobRequest $request)
    {
        // Сохраняем места работы
        if ($request->jobs) {
            foreach ($request->jobs as $job) {
                $userJob = UserJob::where(['id' => ($job['id'] ?? null), 'user_id' => $this->user->id,])->first();
                if (!$userJob) {
                    $userJob = UserJob::create(['user_id' => $this->user->id]);
                }

                $userJob->fill([
                    'company' => $job['company'] ?? null,
                    'website' => $job['website'] ?? null,
                    'position' => $job['position'] ?? null,
                    'description' => $job['description'] ?? null,
                    'year_begin' => $job['year_begin'] ?? null,
                    'year_end' => $job['year_end'] ?? null,
                    'month_begin' => $job['month_begin'] ?? null,
                    'month_end' => $job['month_end'] ?? null,
                    'until_now' => $job['until_now'] ?? null,
                ]);
                $userJob->save();
            }
        }

        // Сохраняем навыки и качества
        $this->user->personal()->update([
            'skills' => $request->skills,
            'qualities' => $request->qualities,
        ]);

        $this->updateUserJobFiles($request);

        return (new UserPersonalResource($this->user->fresh()))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @throws \Exception
     */
    public function destroy(UserJob $job)
    {
        // Удаляем работу
        $job->delete();

        return (new UserPersonalResource($this->user->fresh()))
            ->response()
            ->setStatusCode(200);
    }

    private function updateUserJobFiles(Request $request): void
    {
        $this->syncMediaWithModel($this->user, $request->get('files'), 'job');
    }
}
