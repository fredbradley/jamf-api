<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Resources;

/**
 * Authentication resource.
 *
 * Wraps token lifecycle endpoints for users who need to manage tokens
 * explicitly. In most cases, token management is handled automatically
 * by the authenticator.
 */
class AuthResource extends AbstractResource
{
    /**
     * Retrieve authorization details for the current API token,
     * including account information and assigned privileges.
     *
     * @return array<string,mixed>
     */
    public function details(): array
    {
        return $this->http->get('/v1/auth')->json();
    }

    /**
     * Obtain a new bearer token using the currently configured credentials.
     *
     * Returns the raw token payload including the token string and expiry timestamp.
     *
     * @return array{token: string, expires: string}
     */
    public function token(): array
    {
        return $this->http->post('/v1/auth/token')->json();
    }

    /**
     * Extend the lifetime of the current bearer token.
     *
     * @return array{token: string, expires: string}
     */
    public function keepAlive(): array
    {
        return $this->http->post('/v1/auth/keep-alive')->json();
    }

    /**
     * Revoke / invalidate the current bearer token.
     */
    public function invalidateToken(): void
    {
        $this->http->post('/v1/auth/invalidate-token');
    }
}
