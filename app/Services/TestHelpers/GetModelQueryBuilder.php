<?php

namespace App\Services\TestHelpers;

use App\Services\TestHelpers\interfaces\GetModelQueryBuilderInterface;
use Illuminate\Database\Eloquent\Builder;

class GetModelQueryBuilder implements GetModelQueryBuilderInterface
{
    public function queryBuilder(string $model_class): Builder
    {
        return $model_class::query();
    }
}
