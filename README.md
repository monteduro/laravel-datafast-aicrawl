# Laravel DataFast AI Crawl

Server-side **AI crawler & bot traffic tracking** for Laravel, reporting to [DataFast](https://datafa.st).

See when AI assistants (ChatGPT, Claude, Perplexity…), search crawlers (Googlebot, Bingbot…) and model-training bots (GPTBot, ClaudeBot, CCBot…) request pages on your site. These bots fetch raw HTML and skip frontend JavaScript, so DataFast's browser script never sees them — this package detects them in your backend.

> **Unofficial** PHP port of [`@datafast/ai-crawl`](https://www.npmjs.com/package/@datafast/ai-crawl). Not affiliated with DataFast. The ingestion endpoint it posts to is undocumented and may change.

## Install

```bash
composer require monteduro/laravel-datafast-aicrawl
```

The service provider is auto-discovered and the middleware is auto-registered on the `web` group. Two things to set in `.env`:

```env
# Required — your DataFast website id
DATAFAST_WEBSITE_ID=dfid_xxxxxxxx

# Strongly recommended — run the tracking HTTP call on a real queue
# so it never adds latency to your pages (defaults to the sync driver).
DATAFAST_AICRAWL_QUEUE=default
DATAFAST_AICRAWL_CONNECTION=redis
```

That's the whole setup. Requires PHP 8.2+ and Laravel 11/12.

### About the queue

Every detected crawler request triggers one outbound HTTP POST to DataFast. That call is dispatched as a **best-effort queued job** (no retries, errors swallowed) and the middleware runs *after* the response is sent (`terminate()`), so even on the `sync` driver the visitor is never blocked. Point `DATAFAST_AICRAWL_CONNECTION`/`QUEUE` at a real worker for best throughput.

## How it works

For each `GET`/`HEAD` request the middleware classifies the `User-Agent` against a built-in catalog of ~80 crawlers, skips non-page traffic (static extensions, `/api`, `/_next`, `Sec-Fetch-Dest` sub-resources, …), and if it's a real crawler page hit, queues the event to DataFast.

Payload sent: `websiteId`, `domain`, `href`, `referrer`, and an `ai` object with `provider`, `agent`, `category` (`answer_fetch` / `search_index` / `training` / `ai_crawler`), `userAgent`, `ip`, `statusCode`.

## Configuration

Optional — publish only to customize defaults:

```bash
php artisan vendor:publish --tag=datafast-aicrawl-config
```

| Env var | Default | Purpose |
| --- | --- | --- |
| `DATAFAST_WEBSITE_ID` | — | **Required.** Your DataFast website id. |
| `DATAFAST_AICRAWL_QUEUE` | default | Queue name for the tracking job. |
| `DATAFAST_AICRAWL_CONNECTION` | default | Queue connection. |
| `DATAFAST_AICRAWL_ENABLED` | `true` | Master on/off switch. |
| `DATAFAST_AICRAWL_ENDPOINT` | `https://datafa.st/api/ai-crawls` | Ingestion endpoint. |
| `DATAFAST_AICRAWL_DOMAIN` | request host | Override reported domain. |
| `DATAFAST_AICRAWL_TIMEOUT` | `5` | HTTP timeout (seconds). |
| `DATAFAST_AICRAWL_AUTO_MIDDLEWARE` | `true` | Auto-append middleware to a group. |
| `DATAFAST_AICRAWL_MIDDLEWARE_GROUP` | `web` | Group to append the middleware to. |

Disable crawler categories or tweak ignored paths/extensions in [`config/datafast-aicrawl.php`](config/datafast-aicrawl.php).

To register the middleware manually instead, set `DATAFAST_AICRAWL_AUTO_MIDDLEWARE=false` and attach `\Monteduro\DataFastAiCrawl\Http\Middleware\TrackAiCrawler::class` yourself.

## Keeping the crawler list fresh

The catalog in [`src/Support/CrawlerCatalog.php`](src/Support/CrawlerCatalog.php) is static (ported from the upstream bundle, not fetched at runtime). Periodically diff it against upstream — the equivalent of `npm update`:

```bash
curl -sL https://unpkg.com/@datafast/ai-crawl/dist/index.js
```

## Testing

```bash
composer install && composer test
```

## Credits & License

Crawler catalog and detection logic ported from [`@datafast/ai-crawl`](https://www.npmjs.com/package/@datafast/ai-crawl) (MIT). MIT licensed — see [LICENSE](LICENSE).
