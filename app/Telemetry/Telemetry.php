<?php

namespace App\Telemetry;

use OpenTelemetry\API\Globals;
use OpenTelemetry\Contrib\Otlp\SpanExporter;
use OpenTelemetry\Contrib\Otlp\OtlpHttpTransportFactory;
use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;

class Telemetry
{
    public static function init(): void
    {
        $transport = (new OtlpHttpTransportFactory())
            ->create(
                'http://localhost:4318/v1/traces',
                'application/json'
            );

        $exporter = new SpanExporter($transport);

        $processor = new SimpleSpanProcessor($exporter);

        $provider = new TracerProvider(
            $processor
        );

        Globals::registerTracerProvider($provider);
    }
}
