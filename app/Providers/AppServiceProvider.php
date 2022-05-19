<?php

namespace App\Providers;

use App\Services\TestHelpers\GetModelQueryBuilder;
use App\Services\TestHelpers\interfaces\GetModelQueryBuilderInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        // TEST HELPERS
        GetModelQueryBuilderInterface::class => GetModelQueryBuilder::class,
    ];
}
