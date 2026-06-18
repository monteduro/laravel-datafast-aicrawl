<?php

namespace Monteduro\DataFastAiCrawl;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Monteduro\DataFastAiCrawl\Http\Middleware\TrackAiCrawler;
use Monteduro\DataFastAiCrawl\Support\CrawlerDetector;
use Monteduro\DataFastAiCrawl\Support\CrawlerTracker;

class DataFastAiCrawlServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/datafast-aicrawl.php',
            'datafast-aicrawl',
        );

        $this->app->singleton(CrawlerDetector::class);

        $this->app->singleton(CrawlerTracker::class, function ($app) {
            return new CrawlerTracker(
                $app->make(CrawlerDetector::class),
                $app['config']->get('datafast-aicrawl', []),
            );
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/datafast-aicrawl.php' => $this->app->configPath('datafast-aicrawl.php'),
        ], 'datafast-aicrawl-config');

        $this->registerMiddleware();
    }

    /**
     * Append the tracking middleware to the configured group, unless the user
     * opted out to register it manually.
     */
    protected function registerMiddleware(): void
    {
        $config = $this->app['config']->get('datafast-aicrawl', []);

        if (! ($config['auto_register_middleware'] ?? true)) {
            return;
        }

        $this->app->booted(function () use ($config) {
            $kernel = $this->app->make(Kernel::class);

            if (method_exists($kernel, 'appendMiddlewareToGroup')) {
                $kernel->appendMiddlewareToGroup(
                    $config['middleware_group'] ?? 'web',
                    TrackAiCrawler::class,
                );
            }
        });
    }
}
