<?php

namespace App\Providers;

use App\Services\FileProcessing\FileNameGenerators\FileNameGenerator;
use App\Services\FileProcessing\FileNameGenerators\Interfaces\FileNameGeneratorInterface;
use App\Services\FileProcessing\FileProcessing;
use App\Services\FileProcessing\Interfaces\FileProcessingInterface;
use App\Services\TestHelpers\GetModelQueryBuilder;
use App\Services\TestHelpers\interfaces\GetModelQueryBuilderInterface;
use App\Services\TokenGenerators\CryptTokenGenerator;
use App\Services\TokenGenerators\Interfaces\TokenGeneratorInterface;
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

        // TOKEN GENERATORS
        TokenGeneratorInterface::class => CryptTokenGenerator::class,

        // IMAGE PROCESSING
        FileProcessingInterface::class => FileProcessing::class,
        FileNameGeneratorInterface::class => FileNameGenerator::class,
    ];
}
