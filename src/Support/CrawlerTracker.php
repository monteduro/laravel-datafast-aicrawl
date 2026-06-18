<?php

namespace Monteduro\DataFastAiCrawl\Support;

use Illuminate\Http\Request;
use Monteduro\DataFastAiCrawl\Jobs\SendAiCrawlEvent;

/**
 * Decides whether a request looks like trackable AI crawler traffic and, if so,
 * queues the event to DataFast.
 *
 * Port of the `shouldTrackAICrawlerRequest` + `sendAICrawlerRequest` decision
 * pipeline from @datafast/ai-crawl, adapted to a Laravel Request.
 */
class CrawlerTracker
{
    /**
     * Fetch destinations that indicate a static sub-resource (never tracked).
     *
     * @var array<int, string>
     */
    private const STATIC_FETCH_DESTINATIONS = [
        'audio', 'embed', 'font', 'image', 'manifest',
        'object', 'script', 'style', 'track', 'video', 'worker',
    ];

    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(
        protected CrawlerDetector $detector,
        protected array $config,
    ) {}

    /**
     * Evaluate the request and dispatch a tracking event when applicable.
     */
    public function handle(Request $request, ?int $statusCode = null): void
    {
        $crawler = $this->shouldTrack($request, $statusCode);

        if ($crawler === null) {
            return;
        }

        SendAiCrawlEvent::dispatch(
            $this->buildPayload($request, $crawler, $statusCode),
            (string) $this->config['endpoint'],
            (int) $this->config['timeout'],
        )
            ->onConnection($this->config['connection'] ?? null)
            ->onQueue($this->config['queue'] ?? null);
    }

    /**
     * Returns the matched crawler when the request should be tracked, else null.
     *
     * @return array{provider: string, agent: string, category: string}|null
     */
    public function shouldTrack(Request $request, ?int $statusCode = null): ?array
    {
        if (! ($this->config['enabled'] ?? true)) {
            return null;
        }

        if (empty($this->config['website_id'])) {
            return null;
        }

        $methods = array_map('strtoupper', $this->config['methods'] ?? ['GET', 'HEAD']);
        if (! in_array(strtoupper($request->method()), $methods, true)) {
            return null;
        }

        $crawler = $this->detector->classify($request->userAgent());
        if ($crawler === null) {
            return null;
        }

        if (! $this->categoryEnabled($crawler['category'])) {
            return null;
        }

        $href = $request->fullUrl();
        if (strlen($href) > (int) ($this->config['max_url_length'] ?? 8192)) {
            return null;
        }

        $secFetchDest = strtolower((string) $request->header('sec-fetch-dest', ''));
        if (in_array($secFetchDest, self::STATIC_FETCH_DESTINATIONS, true)) {
            return null;
        }

        $pathname = $this->normalizePathname($request->path());

        foreach ($this->config['ignored_path_prefixes'] ?? [] as $prefix) {
            if ($this->pathStartsWith($pathname, $prefix)) {
                return null;
            }
        }

        if ($this->hasIgnoredExtension($pathname)) {
            return null;
        }

        return $crawler;
    }

    /**
     * @param  array{provider: string, agent: string, category: string}  $crawler
     * @return array<string, mixed>
     */
    private function buildPayload(Request $request, array $crawler, ?int $statusCode): array
    {
        $ai = [
            'provider' => $crawler['provider'],
            'agent' => $crawler['agent'],
            'category' => $crawler['category'],
            'userAgent' => (string) $request->userAgent(),
            'ip' => $this->clientIp($request),
            'source' => 'server_middleware',
        ];

        if ($statusCode !== null) {
            $ai['statusCode'] = $statusCode;
        }

        return [
            'websiteId' => $this->config['website_id'],
            'domain' => $this->config['domain'] ?: $request->getHost(),
            'href' => $request->fullUrl(),
            'referrer' => $request->header('referer') ?: $request->header('referrer'),
            'ai' => $ai,
        ];
    }

    private function categoryEnabled(string $category): bool
    {
        return ($this->config['categories'][$category] ?? true) !== false;
    }

    /**
     * Resolve the client IP using the same proxy header precedence as the JS
     * package, falling back to Laravel's resolved IP.
     */
    private function clientIp(Request $request): ?string
    {
        $headers = [
            'cf-connecting-ip', 'x-real-ip', 'true-client-ip',
            'fastly-client-ip', 'fly-client-ip', 'x-vercel-forwarded-for',
            'x-forwarded-for',
        ];

        foreach ($headers as $header) {
            $value = $request->header($header);
            if ($value) {
                return $this->normalizeIp($value);
            }
        }

        return $request->ip();
    }

    private function normalizeIp(string $value): ?string
    {
        $first = trim(explode(',', $value)[0] ?? '');

        if ($first === '') {
            return null;
        }

        if (str_starts_with($first, '::ffff:')) {
            return substr($first, strlen('::ffff:'));
        }

        return $first;
    }

    private function normalizePathname(string $pathname): string
    {
        if ($pathname === '' || $pathname === '/') {
            return '/';
        }

        $normalized = str_starts_with($pathname, '/') ? $pathname : '/'.$pathname;
        $normalized = preg_replace('#/{2,}#', '/', $normalized);

        return strtolower($normalized);
    }

    private function pathStartsWith(string $pathname, string $prefix): bool
    {
        $normalizedPrefix = $this->normalizePathname($prefix);

        return $pathname === $normalizedPrefix
            || str_starts_with($pathname, $normalizedPrefix.'/');
    }

    private function hasIgnoredExtension(string $pathname): bool
    {
        $extensions = array_map(
            fn (string $ext): string => strtolower(ltrim($ext, '.')),
            $this->config['ignored_extensions'] ?? [],
        );

        $lastSegment = basename($pathname);

        if (preg_match('/\.([a-z0-9]+)$/i', $lastSegment, $matches)) {
            return in_array(strtolower($matches[1]), $extensions, true);
        }

        return false;
    }
}
