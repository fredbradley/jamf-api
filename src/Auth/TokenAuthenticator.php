<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Auth;

use Cranleigh\JamfApi\Auth\Contracts\AuthenticatorInterface;
use Cranleigh\JamfApi\Exceptions\AuthenticationException;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Bearer-token authenticator.
 *
 * Exchanges a Jamf Pro username and password for a bearer token via
 * POST /api/v1/auth/token (Basic auth). Caches the token and automatically
 * keeps it alive using POST /api/v1/auth/keep-alive when it is within
 * 60 seconds of expiry.
 */
final class TokenAuthenticator implements AuthenticatorInterface
{
    private const CACHE_KEY = 'jamf_api_token';

    /** Refresh when fewer than this many seconds remain. */
    private const REFRESH_BUFFER_SECONDS = 60;

    private ?string $token = null;

    private ?Carbon $expiresAt = null;

    public function __construct(
        private readonly string $baseUrl,
        private readonly string $username,
        private readonly string $password,
        private readonly ?string $cacheDriver = null,
    ) {}

    public function getAuthorizationHeader(): string
    {
        if ($this->token === null) {
            $this->initialize();
        }

        return 'Bearer '.$this->token;
    }

    public function shouldRefresh(): bool
    {
        if ($this->token === null || $this->expiresAt === null) {
            return false;
        }

        return $this->expiresAt->diffInSeconds(now(), absolute: true) <= self::REFRESH_BUFFER_SECONDS
            && now()->lt($this->expiresAt);
    }

    public function refresh(): void
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->token,
            'Accept' => 'application/json',
        ])->post($this->baseUrl.'/api/v1/auth/keep-alive');

        if ($response->failed()) {
            // Keep-alive failed; re-authenticate from scratch.
            $this->token = null;
            $this->expiresAt = null;
            $this->initialize();

            return;
        }

        $this->storeToken($response->json());
    }

    /**
     * Fetch a fresh token using Basic authentication.
     *
     * @throws AuthenticationException
     */
    private function initialize(): void
    {
        // Check the cache first.
        $cached = $this->cache()->get(self::CACHE_KEY);

        if ($cached !== null) {
            $this->token = $cached['token'];
            $this->expiresAt = Carbon::parse($cached['expires']);

            return;
        }

        $response = Http::withBasicAuth($this->username, $this->password)
            ->withHeaders(['Accept' => 'application/json'])
            ->post($this->baseUrl.'/api/v1/auth/token');

        if ($response->status() === 401) {
            throw new AuthenticationException(
                'Jamf Pro token authentication failed. Check JAMF_USERNAME and JAMF_PASSWORD.',
                401,
            );
        }

        if ($response->failed()) {
            throw new AuthenticationException(
                'Jamf Pro token authentication request failed: '.$response->status(),
                $response->status(),
            );
        }

        $this->storeToken($response->json());
    }

    /**
     * @param  array<string,mixed>  $payload
     */
    private function storeToken(array $payload): void
    {
        $this->token = $payload['token'];
        $this->expiresAt = Carbon::parse($payload['expires']);

        $this->cache()->put(
            self::CACHE_KEY,
            ['token' => $this->token, 'expires' => $this->expiresAt->toIso8601String()],
            $this->expiresAt,
        );
    }

    private function cache(): Repository
    {
        return Cache::store($this->cacheDriver);
    }
}
