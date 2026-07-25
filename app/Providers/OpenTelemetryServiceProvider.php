<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use OpenTelemetry\SDK\Sdk;

use OpenTelemetry\SDK\Resource\ResourceInfo;
use OpenTelemetry\SDK\Resource\ResourceInfoFactory;
use OpenTelemetry\SDK\Common\Attribute\Attributes;

use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;

use OpenTelemetry\Contrib\Otlp\SpanExporter;
use OpenTelemetry\Contrib\Otlp\OtlpHttpTransportFactory;

use OpenTelemetry\SemConv\ResourceAttributes;


class OpenTelemetryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }


    public function boot(): void
    {
        $endpoint = env(
            'OTEL_EXPORTER_OTLP_ENDPOINT',
            'http://localhost:4318'
        );


        $transport = (new OtlpHttpTransportFactory())
            ->create(
                rtrim($endpoint, '/') . '/v1/traces',
                'application/x-protobuf'
            );


        $exporter = new SpanExporter($transport);


        $attributes = Attributes::create([
            ResourceAttributes::SERVICE_NAME =>
                env('OTEL_SERVICE_NAME', 'myapp'),
        ]);


        $resource = ResourceInfoFactory::defaultResource()
            ->merge(
                ResourceInfo::create($attributes)
            );


        $tracerProvider = new TracerProvider(
            new SimpleSpanProcessor($exporter),
            null,
            $resource
        );


        Sdk::builder()
            ->setTracerProvider($tracerProvider)
            ->setAutoShutdown(true)
            ->buildAndRegisterGlobal();
    }
}