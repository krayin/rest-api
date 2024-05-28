<?php

namespace Webkul\RestApi\Docs\Controllers\Activity;

class ActivityController
{

    /**
     * @OA\Get(
     *     path="/api/v1/activities",
     *     operationId="activityList",
     *     tags={"Activity"},
     *     summary="Get list of activities",
     *     security={ {"sanctum_admin": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Activity")
     *             )
     *         )
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
     * @OA\Get(
     *     path="/api/v1/activities/{id}",
     *     operationId="activityFetch",
     *     tags={"Activity"},
     *     summary="Fetch activity",
     *     security={ {"sanctum_admin": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Activity Id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Activity")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function show()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/activities/is-overlapping",
     *     operationId="activityCheckIfOverlapping",
     *     tags={"Activity"},
     *     summary="Check if activity is overlapping",
     *     security={ {"sanctum_admin": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Activity")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Activity")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function checkIfOverlapping()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/activities",
     *     operationId="activityStore",
     *     tags={"Activity"},
     *     summary="Create activity",
     *     security={ {"sanctum_admin": {} }},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="lead_id",
     *                  title="Lead ID",
     *                  description="ID of the Activity",
     *                  example="1"
     *              ),
     *              @OA\Property(
     *                  property="title",
     *                  title="Title",
     *                  description="Title of the Activity",
     *                  example="Lorem Ipsum"
     *              ),
     *              @OA\Property(
     *                  property="type",
     *                  title="Type",
     *                  description="Type of the Activity",
     *                  example="meeting",
     *                  enum={"call", "meeting", "lunch"}
     *              ),
     *              @OA\Property(
     *                  property="schedule_from",
     *                  title="Schedule From",
     *                  description="Schedule From of the Activity",
     *                  example="2025-09-01 10:00:00"
     *              ),
     *              @OA\Property(
     *                  property="schedule_to",
     *                  title="Schedule To",
     *                  description="Schedule To of the Activity",
     *                  example="2025-11-01 10:00:00"
     *              ),
     *              @OA\Property(
     *                  property="location",
     *                  title="Location",
     *                  description="Location of the Activity",
     *                  example="New York"
     *              ),
     *              @OA\Property(
     *                  property="comment",
     *                  title="Comment",
     *                  description="Comment of the Activity",
     *                  example="Lorem Ipsum"
     *              ),
     *              @OA\Property(
     *                 property="persons",
     *                 type="array",
     *                 @OA\Items(
     *                     type="string",
     *                     example="1"
     *                 ),
     *                 description="List of person IDs"
     *             ),
     *          ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Activity")
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
     * @OA\Put(
     *     path="/api/v1/activities/{id}",
     *     operationId="activityUpdate",
     *     tags={"Activity"},
     *     summary="Update activity",
     *     security={ {"sanctum_admin": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Activity Id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="lead_id",
     *                  title="Lead ID",
     *                  description="ID of the Activity",
     *                  example="1"
     *              ),
     *              @OA\Property(
     *                  property="title",
     *                  title="Title",
     *                  description="Title of the Activity",
     *                  example="Lorem Ipsum"
     *              ),
     *              @OA\Property(
     *                  property="type",
     *                  title="Type",
     *                  description="Type of the Activity",
     *                  example="meeting",
     *                  enum={"call", "meeting", "lunch"}
     *              ),
     *              @OA\Property(
     *                  property="schedule_from",
     *                  title="Schedule From",
     *                  description="Schedule From of the Activity",
     *                  example="2025-09-01 10:00:00"
     *              ),
     *              @OA\Property(
     *                  property="schedule_to",
     *                  title="Schedule To",
     *                  description="Schedule To of the Activity",
     *                  example="2025-11-01 10:00:00"
     *              ),
     *              @OA\Property(
     *                  property="location",
     *                  title="Location",
     *                  description="Location of the Activity",
     *                  example="New York"
     *              ),
     *              @OA\Property(
     *                  property="comment",
     *                  title="Comment",
     *                  description="Comment of the Activity",
     *                  example="Lorem Ipsum"
     *              ),
     *              @OA\Property(
     *                 property="persons",
     *                 type="array",
     *                 @OA\Items(
     *                     type="string",
     *                     example="1"
     *                 ),
     *                 description="List of person IDs"
     *             ),
     *          ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Activity")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function update()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/activities/file-upload",
     *     operationId="activityUpload",
     *     tags={"Activity"},
     *     summary="Upload file",
     *     security={ {"sanctum_admin": {} }},
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="type",
     *                      type="string",
     *                      description="Type of activity",
     *                      example="file"
     *                  ),
     *                  @OA\Property(
     *                      property="lead_id",
     *                      type="string",
     *                      description="Lead id of activity",
     *                      example="1"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="name of activity",
     *                      example="Lorem"
     *                  ),
     *                  @OA\Property(
     *                      property="comment",
     *                      type="string",
     *                      description="comment of activity",
     *                      example="lorem ipsum"
     *                  ),
     *                  @OA\Property(
     *                      property="file",
     *                      type="file",
     *                  ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Activity")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function upload()
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/activities/file-download/{id}",
     *     operationId="activityDownload",
     *     tags={"Activity"},
     *     summary="Download file",
     *     security={ {"sanctum_admin": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Activity Id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Activity")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function download()
    {
    }


    /**
     * @OA\Delete(
     *     path="/api/v1/activities/{id}",
     *     operationId="activityDelete",
     *     tags={"Activity"},
     *     summary="Delete activity",
     *     security={ {"sanctum_admin": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Activity Id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function destroy()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/activities/mass-update",
     *     operationId="activityMassUpdate",
     *     tags={"Activity"},
     *     summary="Mass update activities",
     *     security={ {"sanctum_admin": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="rows",
     *                 type="array",
     *                 description="IDs of the Activities to be updated",
     *                 @OA\Items(
     *                     type="integer",
     *                     example=1
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="value",
     *                 type="string",
     *                 description="Value to be update",
     *                 example="1"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function massUpdate()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/activities/mass-destroy",
     *     operationId="activityMassDestroy",
     *     tags={"Activity"},
     *     summary="Mass destroy activities",
     *     security={ {"sanctum_admin": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="rows",
     *                 type="array",
     *                 description="IDs of the Activities to be deleted",
     *                 @OA\Items(
     *                     type="integer",
     *                     example=1
     *                 )
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function massDestroy()
    {
    }
}
