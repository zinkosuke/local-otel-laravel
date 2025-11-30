<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use OpenTelemetry\SDK\Metrics\MeterProviderFactory;
use Symfony\Component\HttpFoundation\Response;

class RequestTrace
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestStart = time();
        $response = $next($request);
        $requestFinished = time();
        $provider = (new MeterProviderFactory())->create();
        $meter = $provider->getMeter(config("app.name"));
        $requestCounter = $meter->createCounter("http_request_count");
        $requestCounter->add(1);
        $provider->shutdown();
        return $response;
    }
}
