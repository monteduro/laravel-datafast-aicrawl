<?php

namespace Monteduro\DataFastAiCrawl\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Monteduro\DataFastAiCrawl\Support\CrawlerTracker;
use Symfony\Component\HttpFoundation\Response;

/**
 * Terminable middleware that fires AI-crawler tracking after the response has
 * been sent to the client.
 *
 * The actual work happens in terminate(): on FPM this runs after
 * fastcgi_finish_request, so it never adds latency to the response, and the
 * DataFast call itself is queued on top of that.
 */
class TrackAiCrawler
{
    public function __construct(protected CrawlerTracker $tracker) {}

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $this->tracker->handle($request, $response->getStatusCode());
    }
}
