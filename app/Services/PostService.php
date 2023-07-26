<?php 

namespace App\Services;

use App\Repositories\PostRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostService 
{
    /** @var postRepository*/
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * function get list posts
     * @return array
     */
    public function getList()
    {
        $data = $this->postRepository->getList();
        $response = !empty($data) ? $data : collect([]);
        return $response;
    }

    /**
     * function store
     * @param array $data
     * @return array
     */
    public function store(array $data)
    {
        try {
            $result = $this->postRepository->store($data);
        } catch ( \Exception $ex ) {
            Log::error($ex);
            DB::rollBack();
            throw $ex;
        }
        return ['id' => $result->id];
    }

    /**
     * function update post
     * @param array $data
     * @param int $id
     */
    public function update(array $data, int $id)
    {
        try {
            $this->postRepository->update($data, $id);
        } catch ( \Exception $ex ) {
            Log::error($ex);
            DB::rollBack();
            throw $ex;
        }
    }

    /**
     * function delete post
     * @param int $id
     */
    public function destroy(int $id)
    {
        $this->postRepository->destroy($id);
    }

    /**
     * function download item
     * @param int $id
     * 
     */
    public function download(int $id)
    {
        return  $this->postRepository->download($id);
    }
    
    /**
     * function download all items
     */
    public function downloads()
    {
        return $this->postRepository->downloads();
    }

}