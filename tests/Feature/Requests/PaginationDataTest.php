<?php

namespace Tests\Feature\Requests;

use App\Http\Requests\PaginationData;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PaginationDataTest extends TestCase
{
    protected string $route = '/pagination-data-validation-route';

    public function setUp(): void
    {
        parent::setUp();

        Route::post($this->route, function (PaginationData $validate) {
            return true;
        });
    }

    /**
     * @dataProvider successData
     */

    public function testSuccess($limit)
    {
        $this->post($this->route, ['_limit' => $limit])->assertOk();
    }

    /**
     * @dataProvider failedData
     */

    public function testFailed($limit)
    {
        $this->post($this->route, ['_limit' => $limit])->assertStatus(422);
    }

    public function successData()
    {
        return [
            [null],
            [2],
        ];
    }

    public function failedData()
    {
        return [
            ['string'],
            [4.2],
        ];
    }
}
