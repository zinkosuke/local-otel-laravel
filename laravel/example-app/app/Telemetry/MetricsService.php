<?php

namespace App\Telemetry;

use Closure;
use OpenTelemetry\API\Metrics\CounterInterface;
use OpenTelemetry\API\Metrics\MeterInterface;
use OpenTelemetry\SDK\Metrics\MeterProvider;
use OpenTelemetry\SDK\Metrics\MeterProviderFactory;

class MetricsService
{
    private string $prefix;

    private MeterProvider $meterProvider;

    private MeterInterface $meter;

    /** @var array<string, CounterInterface> */
    private array $counters = [];

    public function __construct(string $prefix)
    {
        $this->prefix = $prefix;
        $this->meterProvider = (new MeterProviderFactory)->create();
        $this->meter = $this->meterProvider->getMeter($prefix);
    }

    public function forceFlush(): void
    {
        try {
            $this->meterProvider->forceFlush();
        } catch (\Throwable $e) {
            //
        }
    }

    public function flushScope(Closure $callback): mixed
    {
        try {
            return $callback();
        } finally {
            $this->forceFlush();
        }
    }

    private function getMetricName(string $name): string
    {
        return "{$this->prefix}.{$name}";
    }

    public function getCounter(string $name, string $description = ''): CounterInterface
    {
        $metricName = $this->getMetricName($name);
        if (! isset($this->counters[$metricName])) {
            $this->counters[$metricName] = $this->meter->createCounter(
                $metricName,
                $description
            );
        }

        return $this->counters[$metricName];
    }

    public function increment(
        string $name,
        float|int $value = 1,
        array $attributes = []
    ): void {
        try {
            $this->getCounter($name)->add($value, $attributes);
        } catch (\Throwable $e) {
            //
        }
    }
}
