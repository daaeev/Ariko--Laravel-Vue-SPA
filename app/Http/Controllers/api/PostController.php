<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationData;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Получить все посты
     * 
     * @param PaginationData $validate
     * @return Illuminate\Http\JsonResponse
     */
    public function postsList(PaginationData $validate)
    {
        $perPage = $validate->validated('_limit');

        return response()->json(Post::with('tags')->paginate($perPage));
    }

    /**
     * Получение поста с id = $post_id
     * 
     * @param $post_id
     * @return Illuminate\Http\JsonResponse
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function single($post_id)
    {
        return response()->json(Post::with('tags', 'comments')
            ->where('id', $post_id)
            ->firstOrFail());
    }

    /**
     * Получить все посты с тегом tag
     * 
     * @param $tag
     * @return Illuminate\Http\JsonResponse
     */
    public function postByTag($tag, PaginationData $validate)
    {
        $perPage = $validate->validated('_limit');

        return response()->json(Post::with('tags')
            ->whereRelation('tags', 'name', $tag)
            ->paginate($perPage));
    }
}
