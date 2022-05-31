<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddTagToPost;
use App\Models\PostTag;
use App\Models\Tag;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TagsController extends Controller
{
    /**
     * Получение всех тегов
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tagsList(): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            $this->query_helper
                ->queryBuilder(Tag::class)
                ->get()
        );
    }

    /**
     * Присвоение тега посту
     *
     * @param PostTag $model
     * @param AddTagToPost $validate
     * @return \Illuminate\Http\JsonResponse
     */
    public function addTagToPost(PostTag $model, AddTagToPost $validate): \Illuminate\Http\JsonResponse
    {
        $data = $validate->validated();

        $tag_id = $this->query_helper
            ->queryBuilder(Tag::class)
            ->select('id')
            ->where('name', $data['tag'])
            ->first()
            ?->id;

        // Если тег не найден - создать новый с таким именем
        if (!$tag_id) {
            $new_tag = app(Tag::class);
            $new_tag->fill(['name' => $data['tag']]);

            if (!$new_tag->save()) {
                throw new HttpException(500, 'New tag save failed');
            }

            $tag_id = $new_tag->id;
        }

        $model->fill([
            'tag_id' => $tag_id,
            'post_id' => $data['post_id']
        ]);

        if (!$model->save()) {
            throw new HttpException(500, 'Link model save failed');
        }

        return response()->json($model);
    }
}
