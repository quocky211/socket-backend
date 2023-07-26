<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Post;
use App\Services\PostService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\MediaStream;

class PostController extends Controller
{
    /** @var PostService */
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     *  function index
     * @return 
     */
    public function index()
    {
        $posts = $this->postService->getList();
        return view('posts.index', compact('posts'));
    }

    /**
     *  function create
     * @return 
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * function store post
     * @param StoreRequest $request 
     * @return 
     */
    public function store(StoreRequest $request)
    {
        $idResult = $this->postService->store($request->all());
        return to_route('posts.index');
    }

    /**
     * function edit
     * @return 
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * function update post
     * @param UpdateRequest $request
     * @param $post
     * @return 
     */
    public function update(UpdateRequest $request, $post)
    {
        $this->postService->update($request->all(), $post);
        return to_route('posts.index');
    }

    /**
     * fucntion delete
     * @param Post $post
     * @return 
     */
    public function destroy(Post $post)
    {
        $this->postService->destroy($post->id);
        return to_route('posts.index');
    }

    /**
     * function download item 
     * @param int $id
     * @return Media
     */
    public function download($id)
    {
        return $this->postService->download($id);
    }

    /**
     * function download all item
     * @return MediaStream
     */
    public function downloads()
    {
        return $this->postService->downloads();
    }
}
