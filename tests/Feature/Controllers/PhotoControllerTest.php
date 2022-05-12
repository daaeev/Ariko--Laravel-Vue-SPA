<?php

namespace Tests\Feature\Controllers;

use App\Models\Image;
use App\Models\PhotoWork;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhotoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testPhotosListIfEmpty()
    {
        $this->json('get', route('works.photos'))
            ->assertOk()
            ->assertJson([
                'data' => [],
                'per_page' => 15,
                'current_page' => 1,
                'last_page' => 1,
            ]);
    }

    public function testPhotosListIfExists()
    {
        $works = PhotoWork::factory(2)->create();
        $images1 = Image::factory(2)->create(['photo_work_id' => $works[0]->id]);
        $images2 = Image::factory(2)->create(['photo_work_id' => $works[1]->id]);

        $res_data = [$works[0]->attributesToArray(), $works[1]->attributesToArray()];
        $res_data[0]['images'] = [$images1[0]->attributesToArray(), $images1[1]->attributesToArray()];
        $res_data[1]['images'] = [$images2[0]->attributesToArray(), $images2[1]->attributesToArray()];

        $this->json('get', route('works.photos'))
            ->assertOk()
            ->assertJson([
                'data' => $res_data,
                'per_page' => 15,
                'current_page' => 1,
                'last_page' => 1,
            ]);
    }

    public function testPhotosListPagination()
    {
        PhotoWork::factory(2)->create();

        $this->json('get', route('works.photos', ['_limit' => 1]))
            ->assertOk()
            ->assertJson([
                'per_page' => 1,
                'current_page' => 1,
                'last_page' => 2,
            ]);

        $this->json('get', route('works.photos', ['_limit' => 2]))
            ->assertOk()
            ->assertJson([
                'per_page' => 2,
                'current_page' => 1,
                'last_page' => 1,
            ]);

        $this->json('get', route('works.photos', ['_limit' => 1, 'page' => 2]))
            ->assertOk()
            ->assertJson([
                'per_page' => 1,
                'current_page' => 2,
                'last_page' => 2,
            ]);
    }

    public function testPhotoWorkSingleIfNotExist()
    {
        $this->json('get', route('works.photos.single', ['work_id' => 1]))
            ->assertOk()
            ->assertJson([]);
    }

    public function testPhotoWorkSingleIfExist()
    {
        $work = PhotoWork::factory()->createOne();
        $image = Image::factory()->createOne(['photo_work_id' => $work->id]);

        $data = $work->attributesToArray();
        $data['images'] = [$image->attributesToArray()];

        $this->json('get', route('works.photos.single', ['work_id' => $work->id]))
            ->assertOk()
            ->assertJson($data);
    }

    public function testPhotoWorkNextPrevIds()
    {
        $works = PhotoWork::factory(3)->create();

        $data = ['next' => ['id' => $works[1]->id], 'prev' => null];

        $this->json('get', route('works.photos.next/prev', ['work_id' => $works[0]->id]))
            ->assertOk()
            ->assertJson($data);

        $data = ['next' => ['id' => $works[2]->id], 'prev' => ['id' => $works[0]->id]];

        $this->json('get', route('works.photos.next/prev', ['work_id' => $works[1]->id]))
            ->assertOk()
            ->assertJson($data);

        $data = ['next' => null, 'prev' => ['id' => $works[1]->id]];

        $this->json('get', route('works.photos.next/prev', ['work_id' => $works[2]->id]))
            ->assertOk()
            ->assertJson($data);
    }
}
