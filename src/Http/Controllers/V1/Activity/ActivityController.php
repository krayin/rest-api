<?php

namespace Webkul\RestApi\Http\Controllers\V1\Activity;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\Activity\Repositories\ActivityRepository;
use Webkul\Activity\Repositories\FileRepository;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Activity\ActivityResource;

class ActivityController extends Controller
{
    /**
     * File repository instance.
     *
     * @var \Webkul\Activity\Repositories\FileRepository
     */
    protected $fileRepository;

    /**
     * Activity repository instance.
     *
     * @var \Webkul\Activity\Repositories\ActivityRepository
     */
    protected $activityRepository;

    /**
     * Lead repository instance.
     *
     * @var \Webkul\Lead\Repositories\LeadRepository
     */
    protected $leadRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Activity\Repositories\ActivityRepository  $activityRepository
     * @param  \Webkul\Activity\Repositories\FileRepository  $fileRepository
     * @param  \Webkul\Activity\Repositories\LeadRepository  $leadRepository
     * @return void
     */
    public function __construct(
        ActivityRepository $activityRepository,
        FileRepository $fileRepository,
        LeadRepository $leadRepository
    ) {
        $this->activityRepository = $activityRepository;

        $this->fileRepository = $fileRepository;

        $this->leadRepository = $leadRepository;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resource = $this->activityRepository->find($id);

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
            request('schedule_from'),
            request('schedule_to'),
            request('participants'),
            request('id')
        );

        return response([
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
            'is_done' => request('type') == 'note' ? 1 : 0,
            'user_id' => auth()->guard()->user()->id,
        ]));

        if (request('participants')) {
            if (is_array(request('participants.users'))) {
                foreach (request('participants.users') as $userId) {
                    $activity->participants()->create([
                        'user_id' => $userId,
                    ]);
                }
            }

            if (is_array(request('participants.persons'))) {
                foreach (request('participants.persons') as $personId) {
                    $activity->participants()->create([
                        'person_id' => $personId,
                    ]);
                }
            }
        }

        if (request('lead_id')) {
            $lead = $this->leadRepository->find(request('lead_id'));

            $lead->activities()->attach($activity->id);
        }

        Event::dispatch('activity.create.after', $activity);

        return response([
            'data'    => new ActivityResource($activity),
            'message' => __('admin::app.activities.create-success', ['type' => __('admin::app.activities.' . $activity->type)]),
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

        if (request('participants')) {
            $activity->participants()->delete();

            if (is_array(request('participants.users'))) {
                foreach (request('participants.users') as $userId) {
                    $activity->participants()->create([
                        'user_id' => $userId,
                    ]);
                }
            }

            if (is_array(request('participants.persons'))) {
                foreach (request('participants.persons') as $personId) {
                    $activity->participants()->create([
                        'person_id' => $personId,
                    ]);
                }
            }
        }

        if (request('lead_id')) {
            $lead = $this->leadRepository->find(request('lead_id'));

            if (! $lead->activities->contains($id)) {
                $lead->activities()->attach($id);
            }
        }

        Event::dispatch('activity.update.after', $activity);

        return response([
            'data'    => new ActivityResource($activity),
            'message' => __('admin::app.activities.update-success', ['type' => __('admin::app.activities.' . $activity->type)]),
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

        if ($file) {
            if ($leadId = request('lead_id')) {
                $lead = $this->leadRepository->find($leadId);

                $lead->activities()->attach($file->activity->id);
            }

            Event::dispatch('activities.file.create.after', $file);

            return response([
                'message' => __('admin::app.activities.file-upload-success'),
            ]);
        }

        return response([
            'message' => __('admin::app.activities.file-upload-error'),
        ], 500);
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

            $this->activityRepository->delete($id);

            Event::dispatch('activity.delete.after', $id);

            return response([
                'message' => __('admin::app.activities.destroy-success', ['type' => __('admin::app.activities.' . $activity->type)]),
            ]);
        } catch (\Exception $exception) {
            return response([
                'message' => __('admin::app.activities.destroy-failed', ['type' => __('admin::app.activities.' . $activity->type)]),
            ], 500);
        }
    }

    /**
     * Mass update the specified activities.
     *
     * @return \Illuminate\Http\Response
     */
    public function massUpdate()
    {
        $count = 0;

        foreach (request('rows') as $activityId) {
            Event::dispatch('activity.update.before', $activityId);

            $activity = $this->activityRepository->update([
                'is_done' => request('value'),
            ], $activityId);

            $count++;

            Event::dispatch('activity.update.after', $activity);
        }

        if (! $count) {
            return response([
                'message' => __('admin::app.activities.mass-update-failed'),
            ], 500);
        }

        return response([
            'message' => __('admin::app.activities.mass-update-success'),
        ]);
    }

    /**
     * Mass delete the specified activities.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        foreach (request('rows') as $activityId) {
            Event::dispatch('activity.delete.before', $activityId);

            $this->activityRepository->delete($activityId);

            Event::dispatch('activity.delete.after', $activityId);
        }

        return response([
            'message' => __('admin::app.response.destroy-success', ['name' => __('admin::app.activities.title')]),
        ]);
    }
}
