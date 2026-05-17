<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Tests;

use FredBradley\JamfApi\JamfServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [JamfServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('jamf.base_url', 'https://jamf.example.com');
        $app['config']->set('jamf.auth', 'token');
        $app['config']->set('jamf.username', 'test-user');
        $app['config']->set('jamf.password', 'test-password');
        $app['config']->set('jamf.timeout', 10);
    }
}
