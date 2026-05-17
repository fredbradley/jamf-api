<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Auth\Contracts;

/**
 * Authenticator contract for Jamf Pro API authentication.
 *
 * Implementations handle both token management lifecycle (obtaining, caching,
 * refreshing) and producing the correct Authorization header value.
 */
interface AuthenticatorInterface
{
    /**
     * Return the Authorization header value for the current request.
     *
     * e.g. "Bearer eyJhbGci..." or "Basic dXNlcjpwYXNz"
     */
    public function getAuthorizationHeader(): string;

    /**
     * Whether the current token needs to be refreshed before the next request.
     */
    public function shouldRefresh(): bool;

    /**
     * Perform the token refresh/renewal operation.
     *
     * @throws \Cranleigh\JamfApi\Exceptions\AuthenticationException
     */
    public function refresh(): void;
}
