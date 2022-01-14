<?php

namespace Webkul\RestApi\Http\Controllers\V1\Setting;

use Illuminate\Support\Facades\Event;
use Webkul\RestApi\Http\Controllers\V1\Controller;
use Webkul\RestApi\Http\Resources\V1\Setting\TagResource;
use Webkul\Tag\Repositories\TagRepository;

class TagController extends Controller
{
    /**
     * Tag repository instance.
     *
     * @var \Webkul\Tag\Repositories\TagRepository
     */
    protected $tagRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Tag\Repositories\TagRepository  $tagRepository
     * @return void
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = $this->allResources($this->tagRepository);

        return TagResource::collection($tags);
    }

    /**
     * Show resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $resource = $this->tagRepository->find($id);

        return new TagResource($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|unique:tags,name',
        ]);

        Event::dispatch('settings.tag.create.before');

        $tag = $this->tagRepository->create(array_merge([
            'user_id' => auth()->guard()->user()->id,
        ], request()->all()));

        Event::dispatch('settings.tag.create.after', $tag);

        return response([
            'data'    => new TagResource($tag),
            'message' => __('admin::app.settings.tags.create-success'),
        ]);
    }

    /**
     * Update the specified tag in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'name' => 'required|unique:tags,name',
        ]);

        Event::dispatch('settings.tag.update.before', $id);

        $tag = $this->tagRepository->update(request()->all(), $id);

        Event::dispatch('settings.tag.update.after', $tag);

        return response([
            'data'    => new TagResource($tag),
            'message' => __('admin::app.settings.tags.update-success'),
        ]);
    }

    /**
     * Remove the specified type from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Event::dispatch('settings.tag.delete.before', $id);

            $this->tagRepository->delete($id);

            Event::dispatch('settings.tag.delete.after', $id);

            return response([
                'message' => __('admin::app.settings.tags.delete-success'),
            ]);
        } catch (\Exception $exception) {
            return response([
                'message' => __('admin::app.settings.tags.delete-failed'),
            ], 500);
        }
    }

    /**
     * Mass Delete the specified resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        foreach (request('rows') as $tagId) {
            Event::dispatch('settings.tag.delete.before', $tagId);

            $this->tagRepository->delete($tagId);

            Event::dispatch('settings.tag.delete.after', $tagId);
        }

        return response([
            'message' => __('admin::app.response.destroy-success', ['name' => __('admin::app.settings.tags.title')]),
        ]);
    }
}
