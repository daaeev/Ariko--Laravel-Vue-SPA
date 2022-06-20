<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationData;
use App\Models\VideoWork;

class VideoController extends Controller
{
    /**
     * Получение всех работ (видео)
     *
     * @param PaginationData $validate
     * @return \Illuminate\Http\JsonResponse
     */
    public function videosList(PaginationData $validate): \Illuminate\Http\JsonResponse
    {
        $perPage = $validate->validated('_limit');

        return response()->json(
            $this->query_helper
                ->queryBuilder(VideoWork::class)
                ->paginate($perPage)
        );
    }

    /**
     * Получение работы по идентификатору
     *
     * @param $work_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function single($work_id): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            $this->query_helper
                ->queryBuilder(VideoWork::class)
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
    public function videosNextAndPrevIds($work_id): \Illuminate\Http\JsonResponse
    {
        $builder_next = $this->query_helper->queryBuilder(VideoWork::class);
        $builder_prev = $this->query_helper->queryBuilder(VideoWork::class);

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
