<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateComment;
use App\Models\Comment;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CommentController extends Controller
{
    /**
     * Создание комментария
     *
     * @param Comment $model
     * @param CreateComment $validate
     * @return \Illuminate\Http\JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function createComment(Comment $model, CreateComment $validate): \Illuminate\Http\JsonResponse
    {
        $data = $validate->validated();

        $model->fill($data);

        if (!$model->save()) {
            throw new HttpException(500, 'Save in database failed');
        }

        return response()->json($model);
    }

    /**
     * Получить комментарии поста с id = $post_id
     *
     * @param $post_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function commentsByPost($post_id): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            $this->query_helper
                ->queryBuilder(Comment::class)
                ->where('post_id', $post_id)
                ->get()
        );
    }
}
