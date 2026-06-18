<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Monteduro\DataFastAiCrawl\Jobs\SendAiCrawlEvent;
use Monteduro\DataFastAiCrawl\Support\CrawlerTracker;

function makeRequest(array $server = [], string $uri = 'https://example.com/pricing'): Request
{
    return Request::create($uri, 'GET', [], [], [], array_merge([
        'HTTP_USER_AGENT' => 'Mozilla/5.0 (compatible; GPTBot/1.1; +https://openai.com/gptbot)',
    ], $server));
}

function tracker(array $overrides = []): CrawlerTracker
{
    config()->set('datafast-aicrawl', array_merge(config('datafast-aicrawl'), $overrides));

    return app(CrawlerTracker::class);
}

beforeEach(function () {
    Queue::fake();
});

it('dispatches a tracking job for a known crawler', function () {
    tracker()->handle(makeRequest(), 200);

    Queue::assertPushed(SendAiCrawlEvent::class, function (SendAiCrawlEvent $job) {
        return $job->payload['websiteId'] === 'dfid_test'
            && $job->payload['href'] === 'https://example.com/pricing'
            && $job->payload['ai']['provider'] === 'OpenAI'
            && $job->payload['ai']['agent'] === 'GPTBot'
            && $job->payload['ai']['category'] === 'training'
            && $job->payload['ai']['statusCode'] === 200
            && $job->payload['ai']['source'] === 'server_middleware';
    });
});

it('does not track normal browser traffic', function () {
    tracker()->handle(makeRequest([
        'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0 Safari/537.36',
    ]));

    Queue::assertNothingPushed();
});

it('skips tracking when website id is missing', function () {
    tracker(['website_id' => null])->handle(makeRequest());

    Queue::assertNothingPushed();
});

it('skips tracking when disabled', function () {
    tracker(['enabled' => false])->handle(makeRequest());

    Queue::assertNothingPushed();
});

it('respects disabled categories', function () {
    $config = config('datafast-aicrawl');
    $config['categories']['training'] = false;

    tracker($config)->handle(makeRequest());

    Queue::assertNothingPushed();
});

it('ignores configured path prefixes', function () {
    tracker()->handle(makeRequest(uri: 'https://example.com/api/users'));

    Queue::assertNothingPushed();
});

it('ignores static file extensions', function () {
    tracker()->handle(makeRequest(uri: 'https://example.com/logo.png'));

    Queue::assertNothingPushed();
});

it('skips non GET/HEAD methods', function () {
    $request = Request::create('https://example.com/pricing', 'POST', [], [], [], [
        'HTTP_USER_AGENT' => 'Mozilla/5.0 (compatible; GPTBot/1.1; +https://openai.com/gptbot)',
    ]);

    tracker()->handle($request);

    Queue::assertNothingPushed();
});

it('skips static sec-fetch-dest sub-resource requests', function () {
    tracker()->handle(makeRequest(['HTTP_SEC_FETCH_DEST' => 'image']));

    Queue::assertNothingPushed();
});

it('prefers proxy headers for the client ip', function () {
    tracker()->handle(makeRequest(['HTTP_CF_CONNECTING_IP' => '203.0.113.7']), 200);

    Queue::assertPushed(SendAiCrawlEvent::class, fn (SendAiCrawlEvent $job) => $job->payload['ai']['ip'] === '203.0.113.7');
});

it('omits status code when none is provided', function () {
    tracker()->handle(makeRequest());

    Queue::assertPushed(SendAiCrawlEvent::class, fn (SendAiCrawlEvent $job) => ! array_key_exists('statusCode', $job->payload['ai']));
});
