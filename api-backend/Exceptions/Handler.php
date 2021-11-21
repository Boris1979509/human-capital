<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception $exception
     * @return JsonResponse
     */
    public function render($request, Exception $exception)
    {
        $errorResponse['result'] = 'error';
        $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        switch (true) {
                case $exception instanceof ModelNotFoundException:
                    $errorMessage = 'Record not found';
                    $code = Response::HTTP_NOT_FOUND;
                    break;
                case $exception instanceof EntityNotFoundException:
                    $errorMessage = $exception->getErrorMessage();
                    $code = Response::HTTP_NOT_FOUND;
                    break;
                case $exception instanceof ValidationException:
                    $errorMessage = 'Validation error';
                    $errorResponse['errors'] = $exception->errors();
                    $code = Response::HTTP_UNPROCESSABLE_ENTITY;
                    break;
                case $exception instanceof NotFoundHttpException
                    || $exception instanceof HttpException && $exception->getStatusCode() == Response::HTTP_NOT_FOUND:
                    $errorMessage = $exception->getMessage() ?: 'Resource not found';
                    $code = Response::HTTP_NOT_FOUND;
                    break;
                case $exception instanceof AuthenticationException
                    || $exception instanceof HttpException && $exception->getStatusCode() == Response::HTTP_UNAUTHORIZED:
                    $errorMessage = $exception->getMessage() ?: 'Client is not authenticated';
                    $code = Response::HTTP_UNAUTHORIZED;
                    break;
                case $exception instanceof AuthorizationException
                    || $exception instanceof HttpException && $exception->getStatusCode() == Response::HTTP_FORBIDDEN:
                    $errorMessage = $exception->getMessage() ?: 'Client is not authorized';
                    $code = Response::HTTP_FORBIDDEN;
                    break;
                case $exception instanceof HttpException:
                    $errorMessage = $exception->getMessage();
                    $code = $exception->getStatusCode();
                    break;
                default:
                    $errorMessage = $exception->getMessage();
            }
        $errorResponse['message'] = $errorMessage;

        return response()->json($errorResponse, $code);


        //return parent::render($request, $exception);
    }
}
