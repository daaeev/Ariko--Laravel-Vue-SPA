<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationData;
use App\Models\PhotoWork;

class PhotoController extends Controller
{
    /**
     * Получение всех работ (фотографий)
     *
     * @param PaginationData $validate
     * @return \Illuminate\Http\JsonResponse
     */
    public function photosList(PaginationData $validate): \Illuminate\Http\JsonResponse
    {
        $perPage = $validate->validated('_limit');

        return response()->json(
            $this->query_helper
                ->queryBuilder(PhotoWork::class)
                ->with('images')
                ->whereHas('images')
                ->paginate($perPage)
        );
    }

    /**
     * Получение работы по идентификатору
     *
     * @param $work_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function single($work_id)
    {
        return response()->json(
            $this->query_helper
                ->queryBuilder(PhotoWork::class)
                ->with('images')
                ->where('id', $work_id)
                ->firstOrFail()
        );
    }

    /**
     * Получить идентификаторы 'следующей' и 'предыдущей' работы
     *
     * @param $work_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function photosNextAndPrevIds($work_id)
    {
        $builder_next = $this->query_helper->queryBuilder(PhotoWork::class);
        $builder_prev = $this->query_helper->queryBuilder(PhotoWork::class);

        $next = $builder_next->select('id')
            ->where([['id', '>', $work_id]])
            ->first();

        $prev = $builder_prev->select('id')
            ->where([['id', '<', $work_id]])
            ->orderBy('id', 'desc')
            ->first();

        $res = [
            'next' => $next,
            'prev' => $prev,
        ];

        return response()->json($res);
    }
}
