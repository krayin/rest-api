<?php

namespace Webkul\RestApi\Http\Controllers\V1\Activity;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\Activity\Repositories\ActivityRepository;
use Webkul\Activity\Repositories\FileRepository;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Request\MassDestroyRequest;
use Webkul\RestApi\Http\Request\MassUpdateRequest;
use Webkul\RestApi\Http\Resources\V1\Activity\ActivityResource;

class ActivityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ActivityRepository $activityRepository,
        protected FileRepository $fileRepository,
        protected LeadRepository $leadRepository
    ) {
    }

    /**
     * Returns a listing of the activities.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activities = $this->allResources($this->activityRepository);

        return ActivityResource::collection($activities);
    }

    /**
     * Show resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resource = $this->activityRepository->findOrFail($id);

        return new ActivityResource($resource);
    }

    /**
     * Check if activity duration is overlapping with another activity duration.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkIfOverlapping()
    {
        $isOverlapping = $this->activityRepository->isDurationOverlapping(
            request()->input('schedule_from'),
            request()->input('schedule_to'),
            request()->input('participants'),
            request()->input('id'),
        );

        return new JsonResponse([
            'data' => [
                'overlapping' => $isOverlapping,
            ],
        ]);
    }

    /**
     * Store a newly created activity in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'type'          => 'required',
            'comment'       => 'required_if:type,note',
            'schedule_from' => 'required_unless:type,note',
            'schedule_to'   => 'required_unless:type,note',
        ]);

        Event::dispatch('activity.create.before');

        $activity = $this->activityRepository->create(array_merge(request()->all(), [
            'is_done' => request()->input('type') == 'note',
            'user_id' => auth()->guard()->user()->id,
        ]));

        if (request()->has('participants')) {
            $participantUserIds = request()->input('participants.users', []);

            foreach ($participantUserIds as $userId) {
                $activity->participants()->create(['user_id' => $userId]);
            }

            $participantPersonIds = request()->input('participants.persons', []);

            foreach ($participantPersonIds as $personId) {
                $activity->participants()->create(['person_id' => $personId]);
            }
        }

        if ($leadId = request()->input('lead_id')) {
            if ($lead = $this->leadRepository->find($leadId)) {
                $lead->activities()->attach($activity->id);
            }
        }

        Event::dispatch('activity.create.after', $activity);

        return new JsonResponse([
            'data'    => new ActivityResource($activity),
            'message' => trans('admin::app.activities.create-success', ['type' => trans('admin::app.activities.'.$activity->type)]),
        ]);
    }

    /**
     * Update the specified activity in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        Event::dispatch('activity.update.before', $id);

        $activity = $this->activityRepository->update(request()->all(), $id);

        if (request()->has('participants')) {
            $activity->participants()->delete();

            $participantUserIds = request()->input('participants.users', []);

            foreach ($participantUserIds as $userId) {
                $activity->participants()->create(['user_id' => $userId]);
            }

            $participantPersonIds = request()->input('participants.persons', []);

            foreach ($participantPersonIds as $personId) {
                $activity->participants()->create(['person_id' => $personId]);
            }
        }

        if ($leadId = request()->input('lead_id')) {
            $lead = $this->leadRepository->find($leadId);

            if (! $lead->activities->contains($id)) {
                $lead->activities()->attach($id);
            }
        }

        Event::dispatch('activity.update.after', $activity);

        return new JsonResponse([
            'data'    => new ActivityResource($activity),
            'message' => trans('admin::app.activities.update-success', ['type' => trans('admin::app.activities.'.$activity->type)]),
        ]);
    }

    /**
     * Upload files to storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        $this->validate(request(), [
            'file' => 'required',
        ]);

        Event::dispatch('activities.file.create.before');

        $file = $this->fileRepository->upload(request()->all());

        if (! $file) {
            return new JsonResponse([
                'message' => trans('admin::app.activities.file-upload-error'),
            ], 500);
        }

        if ($leadId = request()->input('lead_id')) {
            if ($lead = $this->leadRepository->find($leadId)) {
                $lead->activities()->attach($file->activity->id);
            }
        }

        Event::dispatch('activities.file.create.after', $file);

        return new JsonResponse([
            'message' => trans('admin::app.activities.file-upload-success'),
        ]);
    }

    /**
     * Download file from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $file = $this->fileRepository->findOrFail($id);

        return Storage::download($file->path);
    }

    /**
     * Remove the specified activity from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $activity = $this->activityRepository->findOrFail($id);

        try {
            Event::dispatch('activity.delete.before', $id);

            $activity?->delete($id);

            Event::dispatch('activity.delete.after', $id);

            return response([
                'message' => trans('admin::app.activities.destroy-success', ['type' => trans('admin::app.activities.'.$activity->type)]),
            ]);
        } catch (\Exception $exception) {
            return response([
                'message' => trans('admin::app.activities.destroy-failed', ['type' => trans('admin::app.activities.'.$activity->type)]),
            ], 500);
        }
    }

    /**
     * Mass update the specified activities.
     *
     * @return \Illuminate\Http\Response
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest)
    {
        $activityIds = $massUpdateRequest->input('indices', []);

        foreach ($activityIds as $activityId) {
            $activity = $this->activityRepository->find($activityId);

            if (! $activity) {
                continue;
            }

            Event::dispatch('activity.update.before', $activityId);

            $activity->update([
                'is_done' => $massUpdateRequest->input('value'),
            ]);

            Event::dispatch('activity.update.after', $activityId);
        }

        return new JsonResponse([
            'message' => trans('admin::app.activities.mass-update-success'),
        ]);
    }

    /**
     * Mass delete the specified activities.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest)
    {
        $activityIds = $massDestroyRequest->input('indices', []);

        foreach ($activityIds as $activityId) {
            $activity = $this->activityRepository->find($activityId);

            if (! $activity) {
                continue;
            }

            Event::dispatch('activity.delete.before', $activityId);

            $activity->delete($activityId);

            Event::dispatch('activity.delete.after', $activityId);
        }

        return new JsonResponse([
            'message' => trans('admin::app.response.destroy-success', ['name' => trans('admin::app.activities.title')]),
        ]);
    }
}
