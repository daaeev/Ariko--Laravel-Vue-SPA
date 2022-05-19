<?php

namespace App\Http\Controllers\api;

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
    public function createComment(Comment $model, CreateComment $validate)
    {
        $data = $validate->validated();

        $model->setRawAttributes($data);

        if ($model->save()) {
            return response()->json($model);
        }

        throw new HttpException(500, 'Save in database failed');
    }

    /**
     * Получить комментарии поста с id = $post_id
     *
     * @param $post_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function commentsByPost($post_id)
    {
        return response()->json(
            $this->query_helper
                ->queryBuilder(Comment::class)
                ->where('post_id', $post_id)
                ->get()
        );
    }
}
