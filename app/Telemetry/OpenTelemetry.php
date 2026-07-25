<?php

namespace App\Telemetry;

use OpenTelemetry\API\Globals;
use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Common\Export\TransportFactory;
use OpenTelemetry\Exporter\Otlp\OtlpHttpTransportFactory;
use OpenTelemetry\Exporter\Otlp\OtlpHttpExporter;

class OpenTelemetry
{
    public static function init()
    {
        $transport = (new OtlpHttpTransportFactory())
            ->create(
                'http://localhost:4318/v1/traces',
                'application/x-protobuf'
            );

        $exporter = new OtlpHttpExporter($transport);

        $processor = new SimpleSpanProcessor($exporter);

        $tracerProvider = new TracerProvider(
            $processor
        );

        Globals::registerTracerProvider(
            $tracerProvider
        );
    }
}

