<?php

namespace Webkul\RestApi\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PDOException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        /**
         * Always answer authentication failures with a JSON 401 for this API.
         */
        $this->renderable(function (AuthenticationException $e, Request $request) {
            return $this->jsonError(401);
        });

        /**
         * Validation failures always render as a JSON 422 with the field errors,
         * regardless of debug mode.
         */
        $this->renderable(function (ValidationException $e, Request $request) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors'  => $e->errors(),
            ], $e->status ?: 422);
        });

        /**
         * Authorization failures render as a JSON 403.
         */
        $this->renderable(function (AuthorizationException $e, Request $request) {
            return $this->jsonError(403);
        });

        /**
         * Missing records (model binding or explicit findOrFail) and unknown
         * routes render as a friendly JSON 404 even in debug mode, so API
         * consumers never receive an HTML stack trace for a not-found resource.
         */
        $this->renderable(function (ModelNotFoundException $e, Request $request) {
            return $this->jsonError(404);
        });

        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            return $this->jsonError(404);
        });

        /**
         * Any remaining exception: keep the framework's default (stack trace)
         * while debugging web/admin routes, but api/* requests always get a
         * clean JSON response — an API consumer should never receive an HTML
         * debug page or trace, regardless of APP_DEBUG.
         */
        $this->renderable(function (Throwable $e, Request $request) {
            if (
                config('app.debug')
                && ! $request->is('api/*')
            ) {
                return null;
            }

            return $this->renderCustomResponse($e, $request)
                ?? ($request->is('api/*') ? $this->jsonError(500) : null);
        });
    }

    /**
     * Localized JSON error messages keyed by status code.
     *
     * @return array<int, string>
     */
    protected function jsonErrorMessages(): array
    {
        return [
            401 => trans('rest-api::app.common.unauthenticated'),
            403 => trans('rest-api::app.common.forbidden-error'),
            404 => trans('rest-api::app.common.resource-not-found'),
            500 => trans('rest-api::app.common.internal-server-error'),
        ];
    }

    /**
     * Build a JSON error response for a given status code.
     *
     * Falls back to the standard HTTP reason phrase (e.g. "Too Many Requests")
     * for status codes without a dedicated localized message, so codes like 405
     * or 429 keep a meaningful body instead of a generic 500 message.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonError(int $statusCode, array $headers = [])
    {
        $message = $this->jsonErrorMessages()[$statusCode]
            ?? (Response::$statusTexts[$statusCode] ?? trans('rest-api::app.common.internal-server-error'));

        return response()->json(['message' => $message], $statusCode, $headers);
    }

    /**
     * Render a custom response for known exception types.
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    protected function renderCustomResponse(Throwable $exception, Request $request)
    {
        if ($exception instanceof HttpException) {
            /**
             * Preserve the exception's real HTTP status code (405, 429, 400,
             * 413, ...) and forward its headers (e.g. Retry-After on a 429,
             * Allow on a 405) instead of collapsing everything to 500. Only a
             * non-error code is coerced to 500.
             */
            $statusCode = $exception->getStatusCode();

            if ($statusCode < 400) {
                $statusCode = 500;
            }

            return $this->response($request, $statusCode, $exception->getHeaders());
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->response($request, 404);
        }

        if ($exception instanceof PDOException || $exception instanceof \ParseError) {
            return $this->response($request, 500);
        }

        return null;
    }

    /**
     * Build the response for a given status code.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function response(Request $request, int $statusCode, array $headers = [])
    {
        if ($request->expectsJson()) {
            return $this->jsonError($statusCode, $headers);
        }

        return response()->view("admin::errors.{$statusCode}", [], $statusCode);
    }
}
