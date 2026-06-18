<?php

namespace Monteduro\DataFastAiCrawl\Tests;

use Monteduro\DataFastAiCrawl\DataFastAiCrawlServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            DataFastAiCrawlServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('datafast-aicrawl.enabled', true);
        $app['config']->set('datafast-aicrawl.website_id', 'dfid_test');
    }
}
