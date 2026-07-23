<?php

namespace Webkul\RestApi\Http\Controllers\V1\Mail;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Webkul\Email\Repositories\EmailRepository;
use Webkul\RestApi\Http\Controllers\V1\Controller;

class TagController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected EmailRepository $emailRepository) {}

    /**
     * Store a newly created resource in storage.
     */
    public function attach(int $id): JsonResponse
    {
        $this->validate(request(), [
            'tag_id' => 'required|exists:tags,id',
        ]);

        $mail = $this->emailRepository->findOrFail($id);

        Event::dispatch('mails.tag.create.before', $id);

        if (! $mail->tags->contains(request()->input('tag_id'))) {
            $mail->tags()->attach(request()->input('tag_id'));
        }

        Event::dispatch('mails.tag.create.after', $mail);

        return response()->json([
            'message' => trans('rest-api::app.mail.view.tags.create-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function detach(int $mailId): JsonResponse
    {
        $mail = $this->emailRepository->findOrFail($mailId);

        $tagId = request()->input('tag_id');

        if (! $mail->tags->contains($tagId)) {
            return response()->json([
                'message' => trans('rest-api::app.common.tag-not-attached'),
            ], 404);
        }

        Event::dispatch('mails.tag.delete.before', $mailId);

        $mail->tags()->detach($tagId);

        Event::dispatch('mails.tag.delete.after', $mail);

        return response()->json([
            'message' => trans('rest-api::app.mail.view.tags.delete-success'),
        ]);
    }
}
