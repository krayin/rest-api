<?php

namespace Webkul\RestApi\Docs\Controllers\Mail;

class EmailController
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
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/mails",
     *     tags={"Mail"},
     *     summary="Store an email",
     *     description="Store an email with the provided data",
     *     operationId="storeEmail",
     *     security={ {"sanctum_admin": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="is_draft",
     *                 type="integer",
     *                 description="Indicates if the email is a draft or not. (0 for false, 1 for true)"
     *             ),
     *             @OA\Property(
     *                 property="id",
     *                 type="integer",
     *                 nullable=true,
     *                 description="The ID of the email",
     *                 example=null,
     *             ),
     *             @OA\Property(
     *                 property="reply_to",
     *                 title="Reply To",
     *                 description="Reply To email addresses",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"reply1@example.com", "reply2@example.com"}
     *             ),
     *             @OA\Property(
     *                 property="cc",
     *                 description="List of email addresses to CC",
     *                 title="CC",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"cc1@example.com", "cc2@example.com"}
     *             ),
     *             @OA\Property(
     *                 property="bcc",
     *                 description="List of email addresses to BCC",
     *                 title="BCC",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"bcc1@example.com", "bcc2@example.com"}
     *             ),
     *             @OA\Property(
     *                 property="subject",
     *                 type="string",
     *                 description="The subject of the email",
     *                 example="subject"
     *             ),
     *             @OA\Property(
     *                 property="reply",
     *                 type="string",
     *                 description="The content of the email reply",
     *                 example="reply"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Email")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function store()
    {
    }

    /**
     * @OA\Get(
     *      path="/api/v1/mails/{id}",
     *      operationId="mailGet",
     *      tags={"Mail"},
     *      summary="Get mail information",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Mail Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\Property(
     *              property="data",
     *              type="Object",
     *              ref="#/components/schemas/Email"
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      )
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Put(
     *      path="/api/v1/mails/{id}",
     *      operationId="mailUpdate",
     *      tags={"Mail"},
     *      summary="Update existing mail",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Mail Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="is_draft",
     *                  type="integer",
     *                  description="Indicates if the email is a draft or not. (0 for false, 1 for true)"
     *              ),
     *              @OA\Property(
     *                  property="id",
     *                  type="integer",
     *                  nullable=true,
     *                  description="The ID of the email",
     *                  example=null,
     *              ),
     *              @OA\Property(
     *                  property="reply_to",
     *                  title="Reply To",
     *                  description="Reply To email addresses",
     *                  type="array",
     *                  @OA\Items(type="string"),
     *                  example={"reply1@example.com", "reply2@example.com"}
     *              ),
     *              @OA\Property(
     *                  property="cc",
     *                  description="List of email addresses to CC",
     *                  title="CC",
     *                  type="array",
     *                  @OA\Items(type="string"),
     *                  example={"cc1@example.com", "cc2@example.com"}
     *              ),
     *              @OA\Property(
     *                  property="bcc",
     *                  description="List of email addresses to BCC",
     *                  title="BCC",
     *                  type="array",
     *                  @OA\Items(type="string"),
     *                  example={"bcc1@example.com", "bcc2@example.com"}
     *              ),
     *              @OA\Property(
     *                  property="subject",
     *                  type="string",
     *                  description="The subject of the email",
     *                  example="subject"
     *              ),
     *              @OA\Property(
     *                  property="reply",
     *                  type="string",
     *                  description="The content of the email reply",
     *                  example="reply"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Email")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      )
     * )
     */
    public function update()
    {
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/mails/{id}",
     *      operationId="mailDelete",
     *      tags={"Mail"},
     *      summary="Delete existing mail",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Mail Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Email")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      )
     * )
     */
    public function destroy()
    {
    }

    /**
     * @OA\Post(
     *      path="/api/v1/mails/mass-update",
     *      operationId="mailMassUpdate",
     *      tags={"Mail"},
     *      summary="Mass update mails",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="rows",
     *                  type="array",
     *                  @OA\Items(
     *                      type="integer"
     *                  )
     *              ),
     *            @OA\Property(
     *                  property="value",
     *                  type="string",
     *                  example="NA"
     *              ),
     *              @OA\Property(
     *                  property="folders",
     *                  type="array",
     *                  @OA\Items(
     *                      type="string",
     *                      example="inbox"
     *                  )
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Email")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      )
     * )
     */
    public function massUpdate()
    {
    }

    /**
     * @OA\Post(
     *      path="/api/v1/mails/mass-destroy",
     *      operationId="mailMassDestroy",
     *      tags={"Mail"},
     *      summary="Mass delete mails",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="rows",
     *                  type="array",
     *                  @OA\Items(
     *                      type="integer"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Email")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      )
     * )
     */
    public function massDestroy()
    {
    }

    /**
     * @OA\Get(
     *      path="/api/v1/mails/attachment-download/{id}",
     *      operationId="mailAttachmentDownload",
     *      tags={"Mail"},
     *      summary="Download attachment",
     *      security={ {"sanctum_admin": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Attachment Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\Property(
     *              property="data",
     *              type="file",
     *              format="binary"
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      )
     * )
     */
    public function download()
    {
    }
}
