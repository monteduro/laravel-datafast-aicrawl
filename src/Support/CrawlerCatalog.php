<?php

namespace Monteduro\DataFastAiCrawl\Support;

/**
 * Static catalog of known AI crawlers / bots.
 *
 * The data lives in resources/crawlers.php and is GENERATED from the upstream
 * npm package @datafast/ai-crawl (MIT). Refresh it with `composer sync-catalog`
 * (or the weekly GitHub Action) rather than editing the data by hand.
 *
 * @see https://www.npmjs.com/package/@datafast/ai-crawl
 */
class CrawlerCatalog
{
    public const CATEGORY_ANSWER_FETCH = 'answer_fetch';
    public const CATEGORY_SEARCH_INDEX = 'search_index';
    public const CATEGORY_TRAINING = 'training';
    public const CATEGORY_AI_CRAWLER = 'ai_crawler';

    /** @var array{version: string, providers: array, aliases: array}|null */
    private static ?array $data = null;

    /** @var array<int, array{provider: string, agent: string, category: string, needle: string}>|null */
    private static ?array $normalized = null;

    /**
     * @return array{version: string, providers: array, aliases: array}
     */
    private static function data(): array
    {
        if (self::$data === null) {
            self::$data = require __DIR__.'/../../resources/crawlers.php';
        }

        return self::$data;
    }

    /**
     * Upstream @datafast/ai-crawl version this catalog was generated from.
     */
    public static function version(): string
    {
        return self::data()['version'] ?? 'unknown';
    }

    /**
     * Flattened exact-agent list with a precomputed lowercase `needle`.
     *
     * @return array<int, array{provider: string, agent: string, category: string, needle: string}>
     */
    public static function agents(): array
    {
        if (self::$normalized !== null) {
            return self::$normalized;
        }

        self::$normalized = [];

        foreach (self::data()['providers'] as $providerConfig) {
            foreach ($providerConfig['agents'] as $agentConfig) {
                self::$normalized[] = [
                    'provider' => $providerConfig['provider'],
                    'agent' => $agentConfig['agent'],
                    'category' => $agentConfig['category'],
                    'needle' => strtolower($agentConfig['agent']),
                ];
            }
        }

        return self::$normalized;
    }

    /**
     * @return array<int, array{provider: string, agent: string, category: string, aliases: array<int, string>}>
     */
    public static function aliases(): array
    {
        return self::data()['aliases'];
    }
}
