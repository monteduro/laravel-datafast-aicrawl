<?php

/*
|--------------------------------------------------------------------------
| AI crawler catalog (DATA)
|--------------------------------------------------------------------------
|
| This file is GENERATED. Do not edit by hand — run:
|
|     composer sync-catalog          # rewrite from latest upstream
|     composer sync-catalog:check    # show drift without writing
|
| It mirrors AI_CRAWLER_PROVIDERS and AI_CRAWLER_CANDIDATE_ALIASES from the
| npm package @datafast/ai-crawl. `version` is the upstream package version
| this data was generated from.
|
| @see https://www.npmjs.com/package/@datafast/ai-crawl
|
*/

return [
    'version' => '1.0.5',

    // Exact agent-token matches (case-insensitive substring of the User-Agent).
    'providers' => [
        ['provider' => 'OpenAI', 'agents' => [
            ['agent' => 'ChatGPT-User', 'category' => 'answer_fetch'],
            ['agent' => 'OAI-SearchBot', 'category' => 'search_index'],
            ['agent' => 'OAI-AdsBot', 'category' => 'ai_crawler'],
            ['agent' => 'GPTBot', 'category' => 'training'],
        ]],
        ['provider' => 'Anthropic', 'agents' => [
            ['agent' => 'Claude-User', 'category' => 'answer_fetch'],
            ['agent' => 'Claude-SearchBot', 'category' => 'search_index'],
            ['agent' => 'ClaudeBot', 'category' => 'training'],
        ]],
        ['provider' => 'Perplexity', 'agents' => [
            ['agent' => 'Perplexity-User', 'category' => 'answer_fetch'],
            ['agent' => 'PerplexityBot', 'category' => 'search_index'],
        ]],
        ['provider' => 'Google', 'agents' => [
            ['agent' => 'Google-InspectionTool', 'category' => 'search_index'],
            ['agent' => 'GoogleOther', 'category' => 'search_index'],
            ['agent' => 'Google-CloudVertexBot', 'category' => 'ai_crawler'],
            ['agent' => 'Google-Agent', 'category' => 'answer_fetch'],
            ['agent' => 'Google-NotebookLM', 'category' => 'answer_fetch'],
            ['agent' => 'Google-Read-Aloud', 'category' => 'answer_fetch'],
            ['agent' => 'Googlebot', 'category' => 'search_index'],
            ['agent' => 'GoogleAgent', 'category' => 'answer_fetch'],
        ]],
        ['provider' => 'Mistral', 'agents' => [
            ['agent' => 'MistralAI-User', 'category' => 'answer_fetch'],
            ['agent' => 'MistralAI-Index', 'category' => 'search_index'],
        ]],
        ['provider' => 'Microsoft', 'agents' => [
            ['agent' => 'Bingbot', 'category' => 'search_index'],
            ['agent' => 'msnbot', 'category' => 'search_index'],
            ['agent' => 'Copilot', 'category' => 'answer_fetch'],
        ]],
        ['provider' => 'Apple', 'agents' => [
            ['agent' => 'Applebot-Extended', 'category' => 'training'],
            ['agent' => 'Applebot', 'category' => 'search_index'],
        ]],
        ['provider' => 'Amazon', 'agents' => [
            ['agent' => 'Amazonbot', 'category' => 'training'],
            ['agent' => 'Amzn-SearchBot', 'category' => 'search_index'],
            ['agent' => 'Amzn-User', 'category' => 'answer_fetch'],
        ]],
        ['provider' => 'DuckDuckGo', 'agents' => [
            ['agent' => 'DuckAssistBot', 'category' => 'answer_fetch'],
        ]],
        ['provider' => 'xAI', 'agents' => [
            ['agent' => 'xAI-SearchBot', 'category' => 'answer_fetch'],
            ['agent' => 'Grok-DeepSearch', 'category' => 'answer_fetch'],
            ['agent' => 'GrokBot', 'category' => 'ai_crawler'],
            ['agent' => 'xAI-Bot', 'category' => 'ai_crawler'],
            ['agent' => 'xAI-Grok', 'category' => 'ai_crawler'],
            ['agent' => 'xAI-Web-Crawler', 'category' => 'ai_crawler'],
            ['agent' => 'Grok', 'category' => 'ai_crawler'],
        ]],
        ['provider' => 'Meta', 'agents' => [
            ['agent' => 'meta-externalagent', 'category' => 'training'],
            ['agent' => 'meta-externalfetcher', 'category' => 'answer_fetch'],
            ['agent' => 'FacebookBot', 'category' => 'ai_crawler'],
        ]],
        ['provider' => 'Moonshot AI', 'agents' => [
            ['agent' => 'Kimi-User', 'category' => 'answer_fetch'],
            ['agent' => 'Kimi-SearchBot', 'category' => 'search_index'],
            ['agent' => 'KimiBot', 'category' => 'training'],
        ]],
        ['provider' => 'ByteDance', 'agents' => [
            ['agent' => 'Doubaobot', 'category' => 'ai_crawler'],
            ['agent' => 'Bytespider', 'category' => 'training'],
            ['agent' => 'TikTokSpider', 'category' => 'search_index'],
        ]],
        ['provider' => 'Baidu', 'agents' => [
            ['agent' => 'ERNIEBot', 'category' => 'training'],
            ['agent' => 'YiyanBot', 'category' => 'ai_crawler'],
            ['agent' => 'Baiduspider', 'category' => 'search_index'],
        ]],
        ['provider' => 'Alibaba', 'agents' => [
            ['agent' => 'Qwen-User', 'category' => 'answer_fetch'],
            ['agent' => 'QwenBot', 'category' => 'training'],
            ['agent' => 'TongyiBot', 'category' => 'ai_crawler'],
            ['agent' => 'AliyunBot', 'category' => 'ai_crawler'],
        ]],
        ['provider' => 'Zhipu AI', 'agents' => [
            ['agent' => 'ChatGLM-Spider', 'category' => 'training'],
        ]],
        ['provider' => 'DeepSeek', 'agents' => [
            ['agent' => 'DeepSeekBot', 'category' => 'training'],
        ]],
        ['provider' => 'Cohere', 'agents' => [
            ['agent' => 'cohere-ai', 'category' => 'training'],
            ['agent' => 'cohere-training-data-crawler', 'category' => 'training'],
        ]],
        ['provider' => 'Allen AI', 'agents' => [
            ['agent' => 'AI2Bot', 'category' => 'training'],
        ]],
        ['provider' => 'You.com', 'agents' => [
            ['agent' => 'YouBot', 'category' => 'search_index'],
        ]],
        ['provider' => 'Common Crawl', 'agents' => [
            ['agent' => 'CCBot', 'category' => 'training'],
        ]],
    ],

    // Fallback alias matches, used only when no exact agent token matches.
    'aliases' => [
        ['provider' => 'OpenAI', 'agent' => 'OpenAI', 'category' => 'ai_crawler', 'aliases' => ['openai', 'chatgpt', 'gptbot', 'oai-', 'oai_', 'openai-search']],
        ['provider' => 'Anthropic', 'agent' => 'Anthropic', 'category' => 'ai_crawler', 'aliases' => ['anthropic', 'claude']],
        ['provider' => 'Perplexity', 'agent' => 'Perplexity', 'category' => 'ai_crawler', 'aliases' => ['perplexity']],
        ['provider' => 'Google', 'agent' => 'Google', 'category' => 'search_index', 'aliases' => ['googlebot', 'googleother', 'google-extended', 'google-inspection', 'google-read-aloud', 'google-notebooklm', 'google-cloudvertex', 'googleagent', 'gemini']],
        ['provider' => 'Microsoft', 'agent' => 'Microsoft', 'category' => 'search_index', 'aliases' => ['bingbot', 'msnbot', 'copilot']],
        ['provider' => 'Apple', 'agent' => 'Apple', 'category' => 'search_index', 'aliases' => ['applebot']],
        ['provider' => 'Amazon', 'agent' => 'Amazon', 'category' => 'ai_crawler', 'aliases' => ['amazonbot', 'amzn-searchbot', 'amzn-user']],
        ['provider' => 'DuckDuckGo', 'agent' => 'DuckDuckGo', 'category' => 'ai_crawler', 'aliases' => ['duckassist', 'duckassistbot']],
        ['provider' => 'xAI', 'agent' => 'xAI', 'category' => 'ai_crawler', 'aliases' => ['xai', 'x-ai', 'grok']],
        ['provider' => 'Meta', 'agent' => 'Meta', 'category' => 'ai_crawler', 'aliases' => ['meta-external', 'facebookbot']],
        ['provider' => 'Mistral', 'agent' => 'Mistral', 'category' => 'ai_crawler', 'aliases' => ['mistralai', 'mistral-ai', 'mistral']],
        ['provider' => 'Moonshot AI', 'agent' => 'Moonshot AI', 'category' => 'ai_crawler', 'aliases' => ['kimi', 'moonshot']],
        ['provider' => 'ByteDance', 'agent' => 'ByteDance', 'category' => 'training', 'aliases' => ['bytespider', 'doubaobot', 'tiktokspider']],
        ['provider' => 'Baidu', 'agent' => 'Baidu', 'category' => 'search_index', 'aliases' => ['baiduspider', 'erniebot', 'yiyanbot']],
        ['provider' => 'Alibaba', 'agent' => 'Alibaba', 'category' => 'ai_crawler', 'aliases' => ['qwen', 'tongyi', 'aliyunbot']],
        ['provider' => 'Zhipu AI', 'agent' => 'Zhipu AI', 'category' => 'training', 'aliases' => ['chatglm', 'zhipu']],
        ['provider' => 'DeepSeek', 'agent' => 'DeepSeek', 'category' => 'training', 'aliases' => ['deepseek']],
        ['provider' => 'Cohere', 'agent' => 'Cohere', 'category' => 'training', 'aliases' => ['cohere']],
        ['provider' => 'Allen AI', 'agent' => 'Allen AI', 'category' => 'training', 'aliases' => ['ai2bot', 'allenai', 'allen-ai']],
        ['provider' => 'You.com', 'agent' => 'You.com', 'category' => 'search_index', 'aliases' => ['youbot', 'you.com']],
        ['provider' => 'Common Crawl', 'agent' => 'Common Crawl', 'category' => 'training', 'aliases' => ['ccbot', 'commoncrawl', 'common-crawl']],
    ],
];
