<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi;

use Cranleigh\JamfApi\Auth\Contracts\AuthenticatorInterface;
use Cranleigh\JamfApi\Auth\OAuthAuthenticator;
use Cranleigh\JamfApi\Auth\TokenAuthenticator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Registers the Jamf Pro API client into the Laravel service container.
 *
 * After installing this package, publish the config with:
 *   php artisan vendor:publish --tag=jamf-config
 *
 * Add the required environment variables to your .env file:
 *   JAMF_BASE_URL=https://yourorg.jamfcloud.com
 *   JAMF_AUTH=token
 *   JAMF_USERNAME=your-api-user
 *   JAMF_PASSWORD=your-password
 */
class JamfServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/jamf.php', 'jamf');

        $this->app->singleton(JamfClient::class, function (Application $app): JamfClient {
            /** @var array<string,mixed> $config */
            $config = $app->make('config')->get('jamf', []);

            $baseUrl     = rtrim((string) ($config['base_url'] ?? ''), '/');
            $authMethod  = (string) ($config['auth'] ?? 'token');
            $cacheDriver = $config['cache_driver'] ?? null;
            $timeout     = (int) ($config['timeout'] ?? 30);

            $auth = match ($authMethod) {
                'oauth' => new OAuthAuthenticator(
                    baseUrl:      $baseUrl,
                    clientId:     (string) ($config['client_id'] ?? ''),
                    clientSecret: (string) ($config['client_secret'] ?? ''),
                    cacheDriver:  $cacheDriver,
                ),
                default => new TokenAuthenticator(
                    baseUrl:     $baseUrl,
                    username:    (string) ($config['username'] ?? ''),
                    password:    (string) ($config['password'] ?? ''),
                    cacheDriver: $cacheDriver,
                ),
            };

            return new JamfClient(
                new JamfHttpClient(
                    baseUrl: $baseUrl,
                    auth:    $auth,
                    timeout: $timeout,
                )
            );
        });

        $this->app->alias(JamfClient::class, 'jamf');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/jamf.php' => $this->app->configPath('jamf.php'),
        ], 'jamf-config');
    }
}
