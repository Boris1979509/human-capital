<?php


namespace App\Http\Controllers\Api\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\CreateApplicantInviteRequest;
use App\Http\Requests\Employer\CreateApplicantRejectionRequest;
use App\Models\Employer\ResponseState\ApplicantInvite;
use App\Models\Employer\ResponseState\ApplicantRejection;
use App\Models\Employer\ResponseState\TransitionToInvited;
use App\Models\Employer\ResponseState\TransitionToRejected;
use App\Models\Employer\ResponseState\TransitionToSeen;
use App\Models\Employer\VacancyResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ResponsesController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function seen(VacancyResponse $vacancyResponse): JsonResponse
    {
        $this->authorize('changeStatus', [VacancyResponse::class, $vacancyResponse]);

        $vacancyResponse->status->transition(new TransitionToSeen($vacancyResponse));

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @throws AuthorizationException
     */
    public function invite(CreateApplicantInviteRequest $request, VacancyResponse $vacancyResponse): JsonResponse
    {
        $this->authorize('changeStatus', [VacancyResponse::class, $vacancyResponse]);

        $invite = new ApplicantInvite(
            $request->get('message'),
            $request->get('interview_type_id'),
            $request->get('contact_phone')
        );

        $vacancyResponse->status->transition(new TransitionToInvited($vacancyResponse, $invite));

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @throws AuthorizationException
     */
    public function reject(CreateApplicantRejectionRequest $request, VacancyResponse $vacancyResponse): JsonResponse
    {
        $this->authorize('changeStatus', [VacancyResponse::class, $vacancyResponse]);

        $rejection = new ApplicantRejection($request->get('message'));

        $vacancyResponse->status->transition(new TransitionToRejected($vacancyResponse, $rejection));

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @throws AuthorizationException
     */
    public function delete(VacancyResponse $response): JsonResponse
    {
        $this->authorize('hideFromUser', [VacancyResponse::class, $response]);

        $response->hideFromUser();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
