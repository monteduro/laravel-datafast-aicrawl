<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | Master switch. When false, no crawler traffic is evaluated or sent. Tip:
    | keep it off in local/testing and on in production.
    |
    */

    'enabled' => env('DATAFAST_AICRAWL_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Website ID
    |--------------------------------------------------------------------------
    |
    | Your DataFast website id (looks like "dfid_xxxxxxxx"). Required: without
    | it, tracking is silently skipped.
    |
    */

    'website_id' => env('DATAFAST_WEBSITE_ID'),

    /*
    |--------------------------------------------------------------------------
    | API endpoint
    |--------------------------------------------------------------------------
    |
    | The DataFast bot-traffic ingestion endpoint. No auth header is required;
    | the website id in the payload identifies the account.
    |
    */

    'endpoint' => env('DATAFAST_AICRAWL_ENDPOINT', 'https://datafa.st/api/ai-crawls'),

    /*
    |--------------------------------------------------------------------------
    | Domain override
    |--------------------------------------------------------------------------
    |
    | Reported as the event domain. Leave null to use the incoming request host.
    |
    */

    'domain' => env('DATAFAST_AICRAWL_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Queue
    |--------------------------------------------------------------------------
    |
    | Connection and queue used to dispatch the (best-effort) tracking job.
    | Null uses your application defaults.
    |
    */

    'connection' => env('DATAFAST_AICRAWL_CONNECTION'),
    'queue' => env('DATAFAST_AICRAWL_QUEUE'),

    /*
    |--------------------------------------------------------------------------
    | HTTP timeout (seconds)
    |--------------------------------------------------------------------------
    */

    'timeout' => (int) env('DATAFAST_AICRAWL_TIMEOUT', 5),

    /*
    |--------------------------------------------------------------------------
    | Auto-register middleware
    |--------------------------------------------------------------------------
    |
    | When true, the package appends its terminable middleware to the group
    | below automatically — no manual kernel/route changes needed. Set to false
    | if you prefer to register \Monteduro\DataFastAiCrawl\Http\Middleware\
    | TrackAiCrawler yourself (e.g. to scope it to specific routes).
    |
    */

    'auto_register_middleware' => env('DATAFAST_AICRAWL_AUTO_MIDDLEWARE', true),
    'middleware_group' => env('DATAFAST_AICRAWL_MIDDLEWARE_GROUP', 'web'),

    /*
    |--------------------------------------------------------------------------
    | Tracked HTTP methods
    |--------------------------------------------------------------------------
    */

    'methods' => ['GET', 'HEAD'],

    /*
    |--------------------------------------------------------------------------
    | Max URL length
    |--------------------------------------------------------------------------
    */

    'max_url_length' => 8192,

    /*
    |--------------------------------------------------------------------------
    | Tracked categories
    |--------------------------------------------------------------------------
    |
    | Disable any category by setting it to false:
    |   - answer_fetch : an AI assistant fetching a page to answer a user
    |   - search_index : search/answer-engine indexing crawlers
    |   - training     : model-training crawlers
    |   - ai_crawler   : other/unclassified AI crawler traffic
    |
    */

    'categories' => [
        'answer_fetch' => true,
        'search_index' => true,
        'training' => true,
        'ai_crawler' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Ignored path prefixes
    |--------------------------------------------------------------------------
    |
    | Requests under these prefixes are never tracked (APIs, framework
    | internals, static folders, etc.).
    |
    */

    'ignored_path_prefixes' => [
        '/api', '/_next', '/_nuxt', '/_astro', '/static', '/assets',
        '/public', '/images', '/img', '/fonts', '/favicon', '/build',
        '/dist', '/admin', '/webhook', '/webhooks', '/cdn-cgi', '/.well-known',
    ],

    /*
    |--------------------------------------------------------------------------
    | Ignored file extensions
    |--------------------------------------------------------------------------
    |
    | Requests whose path ends in one of these extensions are never tracked.
    |
    */

    'ignored_extensions' => [
        'avif', 'bmp', 'br', 'cjs', 'css', 'csv', 'eot', 'gif', 'gz', 'ico',
        'jpeg', 'jpg', 'js', 'json', 'map', 'mjs', 'mov', 'mp3', 'mp4', 'otf',
        'pdf', 'png', 'svg', 'ttf', 'txt', 'wasm', 'wav', 'webm', 'webmanifest',
        'webp', 'woff', 'woff2', 'xml', 'zip',
    ],

];
