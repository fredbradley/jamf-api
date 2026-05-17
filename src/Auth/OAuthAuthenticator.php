<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Auth;

use Cranleigh\JamfApi\Auth\Contracts\AuthenticatorInterface;
use Cranleigh\JamfApi\Exceptions\AuthenticationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * OAuth 2.0 client-credentials authenticator.
 *
 * Requests an access token via POST /api/oauth/token using the
 * client_credentials grant type. The token is cached for its stated TTL.
 * Unlike the token authenticator, no keep-alive is needed — the token is
 * simply re-requested when it expires.
 */
final class OAuthAuthenticator implements AuthenticatorInterface
{
    private const CACHE_KEY = 'jamf_api_oauth_token';

    /** Refresh when fewer than this many seconds remain. */
    private const REFRESH_BUFFER_SECONDS = 30;

    private ?string $accessToken = null;

    private ?Carbon $expiresAt = null;

    public function __construct(
        private readonly string $baseUrl,
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly ?string $cacheDriver = null,
    ) {}

    public function getAuthorizationHeader(): string
    {
        if ($this->accessToken === null) {
            $this->initialize();
        }

        return 'Bearer ' . $this->accessToken;
    }

    public function shouldRefresh(): bool
    {
        if ($this->accessToken === null || $this->expiresAt === null) {
            return false;
        }

        return now()->addSeconds(self::REFRESH_BUFFER_SECONDS)->gt($this->expiresAt);
    }

    public function refresh(): void
    {
        $this->accessToken = null;
        $this->expiresAt   = null;
        $this->cache()->forget(self::CACHE_KEY);
        $this->initialize();
    }

    /**
     * @throws AuthenticationException
     */
    private function initialize(): void
    {
        $cached = $this->cache()->get(self::CACHE_KEY);

        if ($cached !== null) {
            $this->accessToken = $cached['token'];
            $this->expiresAt   = Carbon::parse($cached['expires']);

            return;
        }

        $response = Http::asForm()->post($this->baseUrl . '/api/oauth/token', [
            'grant_type'    => 'client_credentials',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        if ($response->status() === 401) {
            throw new AuthenticationException(
                'Jamf Pro OAuth authentication failed. Check JAMF_CLIENT_ID and JAMF_CLIENT_SECRET.',
                401,
            );
        }

        if ($response->failed()) {
            throw new AuthenticationException(
                'Jamf Pro OAuth token request failed: ' . $response->status(),
                $response->status(),
            );
        }

        $data              = $response->json();
        $this->accessToken = $data['access_token'];
        $ttl               = (int) ($data['expires_in'] ?? 1800);
        $this->expiresAt   = now()->addSeconds($ttl);

        $this->cache()->put(
            self::CACHE_KEY,
            ['token' => $this->accessToken, 'expires' => $this->expiresAt->toIso8601String()],
            $ttl - self::REFRESH_BUFFER_SECONDS,
        );
    }

    private function cache(): \Illuminate\Contracts\Cache\Repository
    {
        return Cache::store($this->cacheDriver);
    }
}
