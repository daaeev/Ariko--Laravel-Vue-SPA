<?php

namespace App\Services\TestHelpers;

use App\Services\TestHelpers\interfaces\GetModelQueryBuilderInterface;
use Illuminate\Database\Eloquent\Builder;

class GetModelQueryBuilder implements GetModelQueryBuilderInterface
{
    public function queryBuilder(string $model_class): Builder
    {
        if (!class_exists($model_class)) {
            throw new \Exception('Class ' . $model_class . ' not exists');
        }

        return $model_class::query();
    }
}
