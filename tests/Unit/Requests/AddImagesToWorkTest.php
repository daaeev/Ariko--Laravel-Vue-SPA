<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\AddImagesToWork;
use App\Models\PhotoWork;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AddImagesToWorkTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'add-images-to-work-validation-test';

    public function setUp(): void
    {
        parent::setUp();

        Route::post($this->route, function (AddImagesToWork $validation) {
            return true;
        });
    }

    public function testSuccess()
    {
        $work = PhotoWork::factory()->createOne();
        $image = new UploadedFile(
            dirname(dirname(__DIR__)) . '/TestFiles/image.png',
            'image.png',
            'image/*',
            null,
            true
        );

        $data = [
            'images' => [
                $image,
                $image
            ],
            'work_id' => $work->id,
        ];

        $this->post($this->route, $data)
            ->assertOk();
    }

    public function testFailed()
    {
        $data = $this->failedData();

        foreach ($data as $req_data) {
            $this->post($this->route, $req_data)
                ->assertStatus(422);
        }
    }

    public function failedData()
    {
        $work = PhotoWork::factory()->createOne();

        $image = new UploadedFile(
            dirname(dirname(__DIR__)) . '/TestFiles/image.png',
            'image.png',
            'image/*',
            null,
            true
        );

        $data = [
            [
                'images' => [$image],
                'work_id' => 0,
            ],
            [
                'images' => [$image],
            ],
            [
                'work_id' => $work->id,
            ],
            [
                'images' => 'not array',
                'work_id' => $work->id,
            ],
            [
                'images' => ['not images'],
                'work_id' => $work->id,
            ],
        ];

        return $data;
    }
}
