<?php

namespace Webkul\RestApi\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as AppExceptionHandler;
use Illuminate\Validation\ValidationException;
use PDOException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends AppExceptionHandler
{
    /**
     * Json error messages.
     *
     * @var array
     */
    protected $jsonErrorMessages = [];

    /**
     * Create handler instance.
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);

        $this->jsonErrorMessages = [
            401 => 'rest-api::app.common.unauthenticated',
            403 => 'rest-api::app.common.forbidden-error',
            404 => 'rest-api::app.common.resource-not-found',
            500 => 'rest-api::app.common.internal-server-error',
        ];
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        if (! config('app.debug')) {
            $response = $this->renderCustomResponse($request, $exception);

            if ($response) {
                return $response;
            }
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => trans($this->jsonErrorMessages[401]),
            ], 401);
        }

        return parent::unauthenticated($request, $exception);
    }

    /**
     * Render custom HTTP response.
     *
     * The validation exception is deliberately left to the framework so the
     * field level error bag is preserved instead of being flattened into a
     * generic message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    private function renderCustomResponse($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return null;
        }

        if ($exception instanceof HttpException) {
            $statusCode = in_array($exception->getStatusCode(), [401, 403, 404, 503])
                ? $exception->getStatusCode()
                : 500;

            return $this->response($request, 'admin', $statusCode);
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->response($request, 'admin', 404);
        }

        if ($exception instanceof PDOException || $exception instanceof \ParseError) {
            return $this->response($request, 'admin', 500);
        }

        return null;
    }

    /**
     * Return custom response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $path
     * @param  int  $statusCode
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function response($request, $path, $statusCode)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => isset($this->jsonErrorMessages[$statusCode])
                    ? trans($this->jsonErrorMessages[$statusCode])
                    : trans('admin::app.common.something-went-wrong'),
            ], $statusCode);
        }

        return response()->view("{$path}::errors.{$statusCode}", [], $statusCode);
    }
}
