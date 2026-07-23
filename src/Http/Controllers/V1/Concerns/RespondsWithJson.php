<?php

namespace Webkul\RestApi\Http\Controllers\V1\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Consistent JSON success/error responses for the V1 controllers.
 */
trait RespondsWithJson
{
    /**
     * Build a consistent success response.
     *
     * Mirrors the `{ data, message }` payload shape already used across the
     * controllers (wrapped by JsonResource under a top-level `data` key).
     */
    protected function respondSuccess(?string $message = null, $data = null): JsonResource
    {
        $payload = [];

        if (! is_null($data)) {
            $payload['data'] = $data;
        }

        if (! is_null($message)) {
            $payload['message'] = $message;
        }

        return new JsonResource($payload);
    }

    /**
     * Build a consistent error response with a real HTTP status code.
     *
     * Fixes the `new JsonResource([...], 500)` anti-pattern where the status
     * code was silently swallowed as the resource payload, so failures were
     * returned as HTTP 200.
     */
    protected function respondError(string $message, int $status = 500): JsonResponse
    {
        return new JsonResponse(['message' => $message], $status);
    }
}
