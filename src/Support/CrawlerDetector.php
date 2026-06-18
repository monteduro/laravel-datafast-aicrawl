<?php

namespace Monteduro\DataFastAiCrawl\Support;

/**
 * Classifies a User-Agent string as a known AI crawler.
 *
 * Faithful port of `classifyAICrawlerUserAgent` from @datafast/ai-crawl:
 * first try an exact agent-token substring match, then fall back to the
 * per-provider alias list.
 */
class CrawlerDetector
{
    /**
     * @return array{provider: string, agent: string, category: string}|null
     */
    public function classify(?string $userAgent): ?array
    {
        if ($userAgent === null || $userAgent === '') {
            return null;
        }

        $normalized = strtolower($userAgent);

        foreach (CrawlerCatalog::agents() as $agent) {
            if (str_contains($normalized, $agent['needle'])) {
                return [
                    'provider' => $agent['provider'],
                    'agent' => $agent['agent'],
                    'category' => $agent['category'],
                ];
            }
        }

        foreach (CrawlerCatalog::aliases() as $candidate) {
            foreach ($candidate['aliases'] as $alias) {
                if (str_contains($normalized, $alias)) {
                    return [
                        'provider' => $candidate['provider'],
                        'agent' => $candidate['agent'],
                        'category' => $candidate['category'],
                    ];
                }
            }
        }

        return null;
    }
}
