<?php 

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\MediaStream;

class PostRepository
{   
    /**
     * function get list posts
     * @return 
     */
    public function getList()
    {
        return Post::query()->get();
    }

    /**
     * @param array $data
     * @return Post
     */
    public function store(array $data)
    {       
        // get param array with post field
        $param = Arr::only($data, ['title', 'body']);
        $post = Post::query()->create($param);
        // check request has input file 'image'
        if(isset($data['image']))
        {
            $post->addMediaFromRequest("ima")
            ->usingName($param['title'])
            ->toMediaCollection('images');
        }
        // check request has input file 'download'
        if(isset($data['download']))
        {
            $post->addMediaFromRequest("download")
            ->usingName($param['title'])
            ->toMediaCollection('downloads');
        }
        return $post;
    }

    /**
     * function update post
     * @param array $data
     * @param int $id
     */
    public function update(array $data, int $id)
    {   
        $param = Arr::only($data, ['title', 'body']);
        // update
        Post::query()->where('id', '=', $id)->update($param);

        $post = Post::findOrFail($id); // not use Post::query()->where('id', '=', $id) because it is a Builder
        // check request has input file 'image'
        if(isset($data['image'])){
            $post->clearMediaCollection('images');
            $post->addMediaFromRequest("image")->usingName($param['title'])->toMediaCollection('images');
        }
        // check request has input file 'download'
        if(isset($data['download'])){
            $post->clearMediaCollection('downloads');
            $post->addMediaFromRequest("download")->usingName($param['title'])->toMediaCollection('downloads');
        }
    }

    /**
     * function delete post
     *  @param int $id
     */
    public function destroy(int $id)
    {
        Post::destroy($id);
    }

    /**
     * function download item
     * @param int $id
     * @return Media
     */
    public function download(int $id)
    {
        $post = Post::findOrFail($id);
        $media = $post->getFirstMedia('downloads');
        return $media;
    }
    
    /**
     * function download all items
     */
    public function downloads()
    {
        return MediaStream::create('downloads.zip')->addMedia(Media::all());
    }
}