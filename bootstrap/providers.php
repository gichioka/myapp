<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\OpenTelemetryServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    OpenTelemetryServiceProvider::class,
];