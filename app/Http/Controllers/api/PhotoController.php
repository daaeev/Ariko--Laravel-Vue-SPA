<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationData;
use App\Models\Image;
use App\Models\PhotoWork;

class PhotoController extends Controller
{
    /**
     * Получение всех работ (фотографий)
     *
     * @param PaginationData $validate
     * @return \Illuminate\Http\JsonResponse
     */
    public function photosList(PaginationData $validate)
    {
        $perPage = $validate->get('_limit');

        return response()
            ->json(
                PhotoWork::with('preview')->paginate($perPage),
                200
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
            PhotoWork::with('images')->find($work_id),
            200
        );
    }

    /**
     * Получение фотографий работы с идентификатором $work_id
     *
     * @param $work_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function singleImages($work_id)
    {
        return response()->json(
            Image::where('photo_work_id', $work_id)->get(),
            200
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
        $next = PhotoWork::select('id')
            ->where([['id', '>', $work_id]])
            ->first();

        $prev = PhotoWork::select('id')
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
