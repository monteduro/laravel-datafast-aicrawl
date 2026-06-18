<?php

namespace Monteduro\DataFastAiCrawl\Support;

/**
 * Static catalog of known AI crawlers / bots.
 *
 * Ported verbatim from the npm package @datafast/ai-crawl (MIT).
 * This list is NOT fetched at runtime: just like running `npm update` on the
 * JS package, you refresh it manually by re-checking the upstream bundle and
 * updating the arrays below when DataFast adds or recategorizes a crawler.
 *
 * @see https://www.npmjs.com/package/@datafast/ai-crawl
 */
class CrawlerCatalog
{
    public const CATEGORY_ANSWER_FETCH = 'answer_fetch';
    public const CATEGORY_SEARCH_INDEX = 'search_index';
    public const CATEGORY_TRAINING = 'training';
    public const CATEGORY_AI_CRAWLER = 'ai_crawler';

    /**
     * Exact-agent matches, keyed by provider. The `agent` token is matched as a
     * case-insensitive substring of the request User-Agent.
     *
     * @var array<int, array{provider: string, agents: array<int, array{agent: string, category: string}>}>
     */
    private const PROVIDERS = [
        [
            'provider' => 'OpenAI',
            'agents' => [
                ['agent' => 'ChatGPT-User', 'category' => self::CATEGORY_ANSWER_FETCH],
                ['agent' => 'OAI-SearchBot', 'category' => self::CATEGORY_SEARCH_INDEX],
                ['agent' => 'OAI-AdsBot', 'category' => self::CATEGORY_AI_CRAWLER],
                ['agent' => 'GPTBot', 'category' => self::CATEGORY_TRAINING],
            ],
        ],
        [
            'provider' => 'Anthropic',
            'agents' => [
                ['agent' => 'Claude-User', 'category' => self::CATEGORY_ANSWER_FETCH],
                ['agent' => 'Claude-SearchBot', 'category' => self::CATEGORY_SEARCH_INDEX],
                ['agent' => 'ClaudeBot', 'category' => self::CATEGORY_TRAINING],
            ],
        ],
        [
            'provider' => 'Perplexity',
            'agents' => [
                ['agent' => 'Perplexity-User', 'category' => self::CATEGORY_ANSWER_FETCH],
                ['agent' => 'PerplexityBot', 'category' => self::CATEGORY_SEARCH_INDEX],
            ],
        ],
        [
            'provider' => 'Google',
            'agents' => [
                ['agent' => 'Google-InspectionTool', 'category' => self::CATEGORY_SEARCH_INDEX],
                ['agent' => 'GoogleOther', 'category' => self::CATEGORY_SEARCH_INDEX],
                ['agent' => 'Google-CloudVertexBot', 'category' => self::CATEGORY_AI_CRAWLER],
                ['agent' => 'Google-Agent', 'category' => self::CATEGORY_ANSWER_FETCH],
                ['agent' => 'Google-NotebookLM', 'category' => self::CATEGORY_ANSWER_FETCH],
                ['agent' => 'Google-Read-Aloud', 'category' => self::CATEGORY_ANSWER_FETCH],
                ['agent' => 'Googlebot', 'category' => self::CATEGORY_SEARCH_INDEX],
                ['agent' => 'GoogleAgent', 'category' => self::CATEGORY_ANSWER_FETCH],
            ],
        ],
        [
            'provider' => 'Mistral',
            'agents' => [
                ['agent' => 'MistralAI-User', 'category' => self::CATEGORY_ANSWER_FETCH],
                ['agent' => 'MistralAI-Index', 'category' => self::CATEGORY_SEARCH_INDEX],
            ],
        ],
        [
            'provider' => 'Microsoft',
            'agents' => [
                ['agent' => 'Bingbot', 'category' => self::CATEGORY_SEARCH_INDEX],
                ['agent' => 'msnbot', 'category' => self::CATEGORY_SEARCH_INDEX],
                ['agent' => 'Copilot', 'category' => self::CATEGORY_ANSWER_FETCH],
            ],
        ],
        [
            'provider' => 'Apple',
            'agents' => [
                ['agent' => 'Applebot-Extended', 'category' => self::CATEGORY_TRAINING],
                ['agent' => 'Applebot', 'category' => self::CATEGORY_SEARCH_INDEX],
            ],
        ],
        [
            'provider' => 'Amazon',
            'agents' => [
                ['agent' => 'Amazonbot', 'category' => self::CATEGORY_TRAINING],
                ['agent' => 'Amzn-SearchBot', 'category' => self::CATEGORY_SEARCH_INDEX],
                ['agent' => 'Amzn-User', 'category' => self::CATEGORY_ANSWER_FETCH],
            ],
        ],
        [
            'provider' => 'DuckDuckGo',
            'agents' => [
                ['agent' => 'DuckAssistBot', 'category' => self::CATEGORY_ANSWER_FETCH],
            ],
        ],
        [
            'provider' => 'xAI',
            'agents' => [
                ['agent' => 'xAI-SearchBot', 'category' => self::CATEGORY_ANSWER_FETCH],
                ['agent' => 'Grok-DeepSearch', 'category' => self::CATEGORY_ANSWER_FETCH],
                ['agent' => 'GrokBot', 'category' => self::CATEGORY_AI_CRAWLER],
                ['agent' => 'xAI-Bot', 'category' => self::CATEGORY_AI_CRAWLER],
                ['agent' => 'xAI-Grok', 'category' => self::CATEGORY_AI_CRAWLER],
                ['agent' => 'xAI-Web-Crawler', 'category' => self::CATEGORY_AI_CRAWLER],
                ['agent' => 'Grok', 'category' => self::CATEGORY_AI_CRAWLER],
            ],
        ],
        [
            'provider' => 'Meta',
            'agents' => [
                ['agent' => 'meta-externalagent', 'category' => self::CATEGORY_TRAINING],
                ['agent' => 'meta-externalfetcher', 'category' => self::CATEGORY_ANSWER_FETCH],
                ['agent' => 'FacebookBot', 'category' => self::CATEGORY_AI_CRAWLER],
            ],
        ],
        [
            'provider' => 'Moonshot AI',
            'agents' => [
                ['agent' => 'Kimi-User', 'category' => self::CATEGORY_ANSWER_FETCH],
                ['agent' => 'Kimi-SearchBot', 'category' => self::CATEGORY_SEARCH_INDEX],
                ['agent' => 'KimiBot', 'category' => self::CATEGORY_TRAINING],
            ],
        ],
        [
            'provider' => 'ByteDance',
            'agents' => [
                ['agent' => 'Doubaobot', 'category' => self::CATEGORY_AI_CRAWLER],
                ['agent' => 'Bytespider', 'category' => self::CATEGORY_TRAINING],
                ['agent' => 'TikTokSpider', 'category' => self::CATEGORY_SEARCH_INDEX],
            ],
        ],
        [
            'provider' => 'Baidu',
            'agents' => [
                ['agent' => 'ERNIEBot', 'category' => self::CATEGORY_TRAINING],
                ['agent' => 'YiyanBot', 'category' => self::CATEGORY_AI_CRAWLER],
                ['agent' => 'Baiduspider', 'category' => self::CATEGORY_SEARCH_INDEX],
            ],
        ],
        [
            'provider' => 'Alibaba',
            'agents' => [
                ['agent' => 'Qwen-User', 'category' => self::CATEGORY_ANSWER_FETCH],
                ['agent' => 'QwenBot', 'category' => self::CATEGORY_TRAINING],
                ['agent' => 'TongyiBot', 'category' => self::CATEGORY_AI_CRAWLER],
                ['agent' => 'AliyunBot', 'category' => self::CATEGORY_AI_CRAWLER],
            ],
        ],
        [
            'provider' => 'Zhipu AI',
            'agents' => [
                ['agent' => 'ChatGLM-Spider', 'category' => self::CATEGORY_TRAINING],
            ],
        ],
        [
            'provider' => 'DeepSeek',
            'agents' => [
                ['agent' => 'DeepSeekBot', 'category' => self::CATEGORY_TRAINING],
            ],
        ],
        [
            'provider' => 'Cohere',
            'agents' => [
                ['agent' => 'cohere-ai', 'category' => self::CATEGORY_TRAINING],
                ['agent' => 'cohere-training-data-crawler', 'category' => self::CATEGORY_TRAINING],
            ],
        ],
        [
            'provider' => 'Allen AI',
            'agents' => [
                ['agent' => 'AI2Bot', 'category' => self::CATEGORY_TRAINING],
            ],
        ],
        [
            'provider' => 'You.com',
            'agents' => [
                ['agent' => 'YouBot', 'category' => self::CATEGORY_SEARCH_INDEX],
            ],
        ],
        [
            'provider' => 'Common Crawl',
            'agents' => [
                ['agent' => 'CCBot', 'category' => self::CATEGORY_TRAINING],
            ],
        ],
    ];

    /**
     * Fallback alias matches, used only when no exact agent token matches.
     *
     * @var array<int, array{provider: string, agent: string, category: string, aliases: array<int, string>}>
     */
    private const ALIASES = [
        ['provider' => 'OpenAI', 'agent' => 'OpenAI', 'category' => self::CATEGORY_AI_CRAWLER, 'aliases' => ['openai', 'chatgpt', 'gptbot', 'oai-', 'oai_', 'openai-search']],
        ['provider' => 'Anthropic', 'agent' => 'Anthropic', 'category' => self::CATEGORY_AI_CRAWLER, 'aliases' => ['anthropic', 'claude']],
        ['provider' => 'Perplexity', 'agent' => 'Perplexity', 'category' => self::CATEGORY_AI_CRAWLER, 'aliases' => ['perplexity']],
        ['provider' => 'Google', 'agent' => 'Google', 'category' => self::CATEGORY_SEARCH_INDEX, 'aliases' => ['googlebot', 'googleother', 'google-extended', 'google-inspection', 'google-read-aloud', 'google-notebooklm', 'google-cloudvertex', 'googleagent', 'gemini']],
        ['provider' => 'Microsoft', 'agent' => 'Microsoft', 'category' => self::CATEGORY_SEARCH_INDEX, 'aliases' => ['bingbot', 'msnbot', 'copilot']],
        ['provider' => 'Apple', 'agent' => 'Apple', 'category' => self::CATEGORY_SEARCH_INDEX, 'aliases' => ['applebot']],
        ['provider' => 'Amazon', 'agent' => 'Amazon', 'category' => self::CATEGORY_AI_CRAWLER, 'aliases' => ['amazonbot', 'amzn-searchbot', 'amzn-user']],
        ['provider' => 'DuckDuckGo', 'agent' => 'DuckDuckGo', 'category' => self::CATEGORY_AI_CRAWLER, 'aliases' => ['duckassist', 'duckassistbot']],
        ['provider' => 'xAI', 'agent' => 'xAI', 'category' => self::CATEGORY_AI_CRAWLER, 'aliases' => ['xai', 'x-ai', 'grok']],
        ['provider' => 'Meta', 'agent' => 'Meta', 'category' => self::CATEGORY_AI_CRAWLER, 'aliases' => ['meta-external', 'facebookbot']],
        ['provider' => 'Mistral', 'agent' => 'Mistral', 'category' => self::CATEGORY_AI_CRAWLER, 'aliases' => ['mistralai', 'mistral-ai', 'mistral']],
        ['provider' => 'Moonshot AI', 'agent' => 'Moonshot AI', 'category' => self::CATEGORY_AI_CRAWLER, 'aliases' => ['kimi', 'moonshot']],
        ['provider' => 'ByteDance', 'agent' => 'ByteDance', 'category' => self::CATEGORY_TRAINING, 'aliases' => ['bytespider', 'doubaobot', 'tiktokspider']],
        ['provider' => 'Baidu', 'agent' => 'Baidu', 'category' => self::CATEGORY_SEARCH_INDEX, 'aliases' => ['baiduspider', 'erniebot', 'yiyanbot']],
        ['provider' => 'Alibaba', 'agent' => 'Alibaba', 'category' => self::CATEGORY_AI_CRAWLER, 'aliases' => ['qwen', 'tongyi', 'aliyunbot']],
        ['provider' => 'Zhipu AI', 'agent' => 'Zhipu AI', 'category' => self::CATEGORY_TRAINING, 'aliases' => ['chatglm', 'zhipu']],
        ['provider' => 'DeepSeek', 'agent' => 'DeepSeek', 'category' => self::CATEGORY_TRAINING, 'aliases' => ['deepseek']],
        ['provider' => 'Cohere', 'agent' => 'Cohere', 'category' => self::CATEGORY_TRAINING, 'aliases' => ['cohere']],
        ['provider' => 'Allen AI', 'agent' => 'Allen AI', 'category' => self::CATEGORY_TRAINING, 'aliases' => ['ai2bot', 'allenai', 'allen-ai']],
        ['provider' => 'You.com', 'agent' => 'You.com', 'category' => self::CATEGORY_SEARCH_INDEX, 'aliases' => ['youbot', 'you.com']],
        ['provider' => 'Common Crawl', 'agent' => 'Common Crawl', 'category' => self::CATEGORY_TRAINING, 'aliases' => ['ccbot', 'commoncrawl', 'common-crawl']],
    ];

    /**
     * Flattened exact-agent list with a precomputed lowercase `needle`.
     *
     * @return array<int, array{provider: string, agent: string, category: string, needle: string}>
     */
    public static function agents(): array
    {
        static $normalized = null;

        if ($normalized !== null) {
            return $normalized;
        }

        $normalized = [];

        foreach (self::PROVIDERS as $providerConfig) {
            foreach ($providerConfig['agents'] as $agentConfig) {
                $normalized[] = [
                    'provider' => $providerConfig['provider'],
                    'agent' => $agentConfig['agent'],
                    'category' => $agentConfig['category'],
                    'needle' => strtolower($agentConfig['agent']),
                ];
            }
        }

        return $normalized;
    }

    /**
     * @return array<int, array{provider: string, agent: string, category: string, aliases: array<int, string>}>
     */
    public static function aliases(): array
    {
        return self::ALIASES;
    }
}
