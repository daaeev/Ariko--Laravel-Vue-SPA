<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationData;
use App\Models\Message;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MessageController extends Controller
{
    /**
     * Получение всех писем
     *
     * @param PaginationData $validate
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function messagesList(PaginationData $validate)
    {
        $perPage = $validate->validated('_limit');

        return response()->json(
            $this->query_helper
                ->queryBuilder(Message::class)
                ->paginate($perPage)
        );
    }

    /**
     * Удаление письма
     *
     * @param \App\Models\Message $model
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMessage(Message $model)
    {
        $model_id = $model->id;

        if (!$model->delete()) {
            throw new HttpException(500, 'Model delete failed');
        }

        return response()->json(['id' => $model_id]);
    }
}
