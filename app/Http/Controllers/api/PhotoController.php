<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaginationData;
use App\Models\PhotoWork;

class PhotoController extends Controller
{
    public function photosList(PaginationData $validate)
    {
        $perPage = $validate->get('_limit');

        return response()
            ->json(
                PhotoWork::with('images')->paginate($perPage),
                200
            );
    }
}
