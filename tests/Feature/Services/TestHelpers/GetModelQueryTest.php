<?php

namespace Tests\Feature\Services\TestHelpers;

use App\Services\TestHelpers\GetModelQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Tests\TestCase;

class GetModelQueryTest extends TestCase
{
    public function testSuccess()
    {
        $instance = app(GetModelQueryBuilder::class);

        $query = $instance->queryBuilder('\App\Models\User');
        $this->assertInstanceOf(Builder::class, $query);
    }

    public function testClassNotExist()
    {
        $instance = app(GetModelQueryBuilder::class);

        $this->expectException(\Exception::class);
        $instance->queryBuilder('\Class\Not\Exists');
    }
}
