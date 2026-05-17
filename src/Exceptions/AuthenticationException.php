<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Exceptions;

/**
 * Thrown when the Jamf Pro API returns HTTP 401 Unauthorized.
 *
 * This typically means the token has expired or credentials are invalid.
 */
class AuthenticationException extends JamfException {}
