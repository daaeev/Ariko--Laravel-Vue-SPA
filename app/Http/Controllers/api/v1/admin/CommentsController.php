<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationData;
use App\Http\Requests\SetCommentCheckedStatus;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CommentsController extends Controller
{
    /**
     * Получение всех комментариев
     *
     * @param PaginationData $validate
     * @return JsonResponse
     */
    public function commentsList(PaginationData $validate): JsonResponse
    {
        $perPage = $validate->validated('_limit');

        return response()->json(
            $this->query_helper
                ->queryBuilder(Comment::class)
                ->paginate($perPage)
        );
    }

    /**
     * Удаление комментария
     *
     * @param Comment $model
     * @return JsonResponse
     */
    public function deleteComment(Comment $model): JsonResponse
    {
        $model_id = $model->id;

        if (!$model->delete()) {
            throw new HttpException(500, 'Model delete failed');
        }

        return response()->json(['id' => $model_id]);
    }

    /**
     * Изменение статуса комментария на статус 'Проверено'
     *
     * @param Comment $model
     * @return JsonResponse
     */
    public function setCheckedStatus(Comment $model): JsonResponse
    {
        if (!$model->update(['checked' => true])) {
            throw new HttpException(500, 'Set checked status failed');
        }

        return response()->json($model);
    }
}
