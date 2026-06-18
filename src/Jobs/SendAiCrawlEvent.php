<?php

namespace Monteduro\DataFastAiCrawl\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Best-effort POST of a single bot-traffic event to the DataFast API.
 *
 * Tracking must never disrupt the application: this job runs on the queue and
 * swallows transport errors. It does not retry, by design, to avoid flooding
 * the API with duplicates for crawler traffic that is itself best-effort data.
 */
class SendAiCrawlEvent implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** Do not pile up retries for best-effort analytics. */
    public int $tries = 1;

    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public array $payload,
        public string $endpoint,
        public int $timeout = 5,
    ) {}

    public function handle(): void
    {
        try {
            Http::timeout($this->timeout)
                ->acceptJson()
                ->asJson()
                ->post($this->endpoint, $this->payload);
        } catch (Throwable $e) {
            // Best-effort: never let crawler tracking surface as an app error.
        }
    }
}
