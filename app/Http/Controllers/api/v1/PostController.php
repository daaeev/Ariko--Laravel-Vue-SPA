<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationData;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Получить все посты
     *
     * @param PaginationData $validate
     * @return \Illuminate\Http\JsonResponse
     */
    public function postsList(PaginationData $validate): \Illuminate\Http\JsonResponse
    {
        $perPage = $validate->validated('_limit');

        return response()->json(
            $this->query_helper
                ->queryBuilder(Post::class)
                ->with('tags')
                ->paginate($perPage)
        );
    }

    /**
     * Получение поста с id = $post_id
     *
     * @param $post_id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function single($post_id): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            $this->query_helper
                ->queryBuilder(Post::class)
                ->with('tags', 'comments')
                ->where('id', $post_id)
                ->firstOrFail()
        );
    }

    /**
     * Получить все посты с тегом tag
     *
     * @param $tag
     * @return \Illuminate\Http\JsonResponse
     */
    public function postByTag($tag, PaginationData $validate): \Illuminate\Http\JsonResponse
    {
        $perPage = $validate->validated('_limit');

        return response()->json(
            $this->query_helper
                ->queryBuilder(Post::class)
                ->with('tags')
                ->whereRelation('tags', 'name', $tag)
                ->paginate($perPage)
        );
    }
}
