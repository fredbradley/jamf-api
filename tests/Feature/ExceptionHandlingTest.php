<?php

declare(strict_types=1);

use Cranleigh\JamfApi\Exceptions\AuthenticationException;
use Cranleigh\JamfApi\Exceptions\ForbiddenException;
use Cranleigh\JamfApi\Exceptions\NotFoundException;
use Cranleigh\JamfApi\Exceptions\RateLimitException;
use Cranleigh\JamfApi\Exceptions\ServerException;
use Cranleigh\JamfApi\Facades\Jamf;
use Illuminate\Support\Facades\Http;

beforeEach(function (): void {
    Http::preventStrayRequests();

    Http::fake([
        'jamf.example.com/api/v1/auth/token' => Http::response([
            'token'   => 'token',
            'expires' => now()->addMinutes(30)->toIso8601String(),
        ]),
    ]);
});

it('throws NotFoundException on 404', function (): void {
    Http::fake([
        'jamf.example.com/api/v1/scripts/999' => Http::response(['detail' => 'Not found'], 404),
    ]);

    expect(fn () => Jamf::scripts()->find('999'))->toThrow(NotFoundException::class);
});

it('throws ForbiddenException on 403', function (): void {
    Http::fake([
        'jamf.example.com/api/v1/scripts*' => Http::response([], 403),
    ]);

    expect(fn () => Jamf::scripts()->list())->toThrow(ForbiddenException::class);
});

it('throws RateLimitException on 429', function (): void {
    Http::fake([
        'jamf.example.com/api/v1/scripts*' => Http::response([], 429),
    ]);

    expect(fn () => Jamf::scripts()->list())->toThrow(RateLimitException::class);
});

it('throws ServerException on 500', function (): void {
    Http::fake([
        'jamf.example.com/api/v1/scripts*' => Http::response([], 500),
    ]);

    expect(fn () => Jamf::scripts()->list())->toThrow(ServerException::class);
});
