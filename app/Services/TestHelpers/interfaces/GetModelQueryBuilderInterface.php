<?php

namespace App\Services\TestHelpers\interfaces;

use Illuminate\Database\Eloquent\Builder;

interface GetModelQueryBuilderInterface
{
    /**
     * Метод возвращает построитель запросов для модели $model_class
     *
     * @param string $model_class
     * @return Builder
     */
    public function queryBuilder(string $model_class): Builder;
}
