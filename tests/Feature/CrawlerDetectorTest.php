<?php

use Monteduro\DataFastAiCrawl\Support\CrawlerDetector;

beforeEach(function () {
    $this->detector = new CrawlerDetector();
});

it('returns null for empty user agents', function () {
    expect($this->detector->classify(null))->toBeNull();
    expect($this->detector->classify(''))->toBeNull();
});

it('returns null for a normal browser user agent', function () {
    $ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36';

    expect($this->detector->classify($ua))->toBeNull();
});

it('classifies known crawlers by exact agent token', function (string $ua, string $provider, string $agent, string $category) {
    expect($this->detector->classify($ua))->toMatchArray([
        'provider' => $provider,
        'agent' => $agent,
        'category' => $category,
    ]);
})->with([
    'GPTBot' => ['Mozilla/5.0 (compatible; GPTBot/1.1; +https://openai.com/gptbot)', 'OpenAI', 'GPTBot', 'training'],
    'ClaudeBot' => ['Mozilla/5.0 (compatible; ClaudeBot/1.0; +claudebot@anthropic.com)', 'Anthropic', 'ClaudeBot', 'training'],
    'Claude-User' => ['Mozilla/5.0 (compatible; Claude-User/1.0)', 'Anthropic', 'Claude-User', 'answer_fetch'],
    'Googlebot' => ['Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'Google', 'Googlebot', 'search_index'],
    'PerplexityBot' => ['Mozilla/5.0 (compatible; PerplexityBot/1.0)', 'Perplexity', 'PerplexityBot', 'search_index'],
    'Bytespider' => ['Mozilla/5.0 (compatible; Bytespider; spider-feedback@bytedance.com)', 'ByteDance', 'Bytespider', 'training'],
]);

it('falls back to alias matching when no exact token matches', function () {
    // No exact agent token, but contains the "gemini" alias.
    expect($this->detector->classify('SomeBot gemini/2.0'))->toMatchArray([
        'provider' => 'Google',
        'agent' => 'Google',
        'category' => 'search_index',
    ]);
});
