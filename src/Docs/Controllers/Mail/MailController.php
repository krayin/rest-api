<?php

namespace Webkul\RestApi\Docs\Controllers\Mail;

class MailController
{
    /**
     * @OA\Get(
     *      path="/api/v1/mails",
     *      operationId="mailList",
     *      tags={"Mail"},
     *      summary="Get list of mails",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *               @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Email")
     *              )
     *          )
     *      ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index()
    {
    }
}
