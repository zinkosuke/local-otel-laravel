<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        return \Metrics::flushScope(function () use ($request, $next) {
            \Metrics::increment('http_request_count', attributes: [
                'uri' => \Route::getCurrentRoute() ? \Route::getCurrentRoute()->uri : null,
            ]);
            $response = $next($request);

            return $response;
        });
    }
}
