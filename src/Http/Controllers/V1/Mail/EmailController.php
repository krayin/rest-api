<?php

namespace Webkul\RestApi\Http\Controllers\V1\Mail;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Email\Mails\Email;
use Webkul\Email\Repositories\AttachmentRepository;
use Webkul\Email\Repositories\EmailRepository;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Request\MassDestroyRequest;
use Webkul\RestApi\Http\Request\MassUpdateRequest;
use Webkul\RestApi\Http\Resources\V1\Email\EmailResource;

class EmailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected LeadRepository $leadRepository,
        protected EmailRepository $emailRepository,
        protected AttachmentRepository $attachmentRepository,
        protected AttributeRepository $attributeRepository
    ) {}

    /**
     * Display a listing of the emails.
     */
    public function index(): JsonResource
    {
        $emails = $this->allResources($this->emailRepository);

        return EmailResource::collection($emails);
    }

    /**
     * Show resource.
     */
    public function show($id): JsonResource
    {
        $resource = $this->findOrFailResource($this->emailRepository, $id);

        return new EmailResource($resource);
    }

    /**
     * Store a newly created email in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'reply_to'   => 'required|array|min:1',
            'reply_to.*' => 'email',
            'reply'      => 'required',
        ]);

        Event::dispatch('email.create.before');

        $data = request()->all();

        $isDraft = request()->boolean('is_draft');

        $data['folders'] = $isDraft ? ['draft'] : ['outbox'];

        $email = $this->emailRepository->create($data);

        if (! $isDraft) {
            try {
                Mail::send(new Email($email));

                $this->emailRepository->update([
                    'folders' => ['inbox', 'sent'],
                ], $email->id);
            } catch (\Exception $e) {
                /**
                 * Delivery failed: log it instead of swallowing silently. The
                 * email keeps the `outbox` folder set above (it is not marked
                 * sent), so a failed send stays distinguishable and debuggable.
                 */
                report($e);
            }
        }

        Event::dispatch('email.create.after', $email);

        return response()->json([
            'data'    => new EmailResource($email),
            'message' => trans($isDraft ? 'rest-api::app.mail.saved-to-draft' : 'rest-api::app.mail.create-success'),
        ]);
    }

    /**
     * Update the specified email in storage.
     */
    public function update(int $id)
    {
        $this->findOrFailResource($this->emailRepository, $id);

        Event::dispatch('email.update.before', $id);

        $data = request()->all();

        $isDraft = request()->boolean('is_draft');

        $data['is_draft'] = $isDraft;
        $data['folders']  = $isDraft ? ['draft'] : ['outbox'];
        $data['source']   = 'web';

        $email = $this->emailRepository->update($data, $id);

        Event::dispatch('email.update.after', $email);

        if (! $isDraft) {
            try {
                Mail::send(new Email($email));

                $this->emailRepository->update([
                    'folders' => ['inbox', 'sent'],
                ], $email->id);
            } catch (\Exception $e) {
                /**
                 * Delivery failed: log it instead of swallowing silently. The
                 * email keeps the `outbox` folder set above (it is not marked
                 * sent), so a failed send stays distinguishable and debuggable.
                 */
                report($e);
            }
        }

        return response()->json([
            'data'    => new EmailResource($email),
            'message' => trans($isDraft ? 'rest-api::app.mail.saved-to-draft' : 'rest-api::app.mail.update-success'),
        ]);
    }

    /**
     * Remove the specified email from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->emailRepository->findOrFail($id);

        $type = request()->input('type', 'delete');

        try {
            Event::dispatch("email.$type.before", $id);

            if ($type == 'trash') {
                $this->emailRepository->update([
                    'folders' => ['trash'],
                ], $id);
            } else {
                $this->emailRepository->delete($id);
            }

            Event::dispatch("email.$type.after", $id);

            return $this->respondSuccess(trans(
                $type == 'trash'
                    ? 'rest-api::app.mail.trash-success'
                    : 'rest-api::app.mail.delete-success'
            ));
        } catch (\Exception $exception) {
            return $this->respondError(trans('rest-api::app.mail.delete-failed'), 500);
        }
    }

    /**
     * Mass update the specified emails.
     *
     * @return \Illuminate\Http\Response
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest)
    {
        $this->validate(request(), [
            'folders' => 'required|array|min:1',
        ]);

        $count = 0;

        foreach ($massUpdateRequest->input('indices', []) as $emailId) {
            $email = $this->emailRepository->find($emailId);

            if (! $email) {
                continue;
            }

            Event::dispatch('email.update.before', $emailId);

            $email->update([
                'folders' => request()->input('folders'),
            ]);

            Event::dispatch('email.update.after', $emailId);

            $count++;
        }

        if (! $count) {
            return $this->respondError(trans('rest-api::app.common.nothing-to-delete'), 404);
        }

        return $this->respondSuccess(trans('rest-api::app.mail.mass-update-success'));
    }

    /**
     * Mass delete the specified emails.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest)
    {
        $emails = $massDestroyRequest->input('indices', []);

        $type = request()->input('type', 'delete');

        $count = 0;

        foreach ($emails as $emailId) {
            $email = $this->emailRepository->find($emailId);

            if (! $email) {
                continue;
            }

            Event::dispatch("email.$type.before", $emailId);

            if ($type == 'trash') {
                $email->update([
                    'folders' => ['trash'],
                ]);
            } else {
                $email->delete();
            }

            Event::dispatch("email.$type.after", $emailId);

            $count++;
        }

        if (! $count) {
            return $this->respondError(trans('rest-api::app.common.nothing-to-delete'), 404);
        }

        return $this->respondSuccess(trans('rest-api::app.mail.destroy-success'));
    }

    /**
     * Download file from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $attachment = $this->attachmentRepository->findOrFail($id);

        return Storage::download($attachment->path);
    }
}
