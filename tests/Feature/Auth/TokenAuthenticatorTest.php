<?php

declare(strict_types=1);

use Cranleigh\JamfApi\Auth\TokenAuthenticator;
use Cranleigh\JamfApi\Exceptions\AuthenticationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(fn () => Cache::flush());

it('obtains a bearer token via basic auth', function (): void {
    Http::fake([
        'jamf.example.com/api/v1/auth/token' => Http::response([
            'token' => 'my-test-token',
            'expires' => now()->addMinutes(30)->toIso8601String(),
        ]),
    ]);

    $auth = new TokenAuthenticator(
        baseUrl: 'https://jamf.example.com',
        username: 'user',
        password: 'pass',
    );

    expect($auth->getAuthorizationHeader())->toBe('Bearer my-test-token');
});

it('caches the token and does not re-request on second call', function (): void {
    Http::fake([
        'jamf.example.com/api/v1/auth/token' => Http::response([
            'token' => 'cached-token',
            'expires' => now()->addMinutes(30)->toIso8601String(),
        ]),
    ]);

    $auth = new TokenAuthenticator('https://jamf.example.com', 'user', 'pass');

    $auth->getAuthorizationHeader();
    $auth->getAuthorizationHeader();

    Http::assertSentCount(1);
});

it('throws AuthenticationException on 401', function (): void {
    Http::fake([
        'jamf.example.com/api/v1/auth/token' => Http::response([], 401),
    ]);

    $auth = new TokenAuthenticator('https://jamf.example.com', 'bad', 'creds');

    expect(fn () => $auth->getAuthorizationHeader())->toThrow(AuthenticationException::class);
});

it('returns false for shouldRefresh when token is fresh', function (): void {
    Http::fake([
        'jamf.example.com/api/v1/auth/token' => Http::response([
            'token' => 'fresh-token',
            'expires' => now()->addMinutes(30)->toIso8601String(),
        ]),
    ]);

    $auth = new TokenAuthenticator('https://jamf.example.com', 'user', 'pass');
    $auth->getAuthorizationHeader();

    expect($auth->shouldRefresh())->toBeFalse();
});
