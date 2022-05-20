<?php

namespace App\Providers;

use App\Services\TestHelpers\GetModelQueryBuilder;
use App\Services\TestHelpers\interfaces\GetModelQueryBuilderInterface;
use App\Services\TokenValidators\CryptTokenValidator;
use App\Services\TokenValidators\interfaces\AuthTokenValidatorInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        // TEST HELPERS
        GetModelQueryBuilderInterface::class => GetModelQueryBuilder::class,

        // TOKEN VALIDATORS
        AuthTokenValidatorInterface::class => CryptTokenValidator::class,
    ];
}
