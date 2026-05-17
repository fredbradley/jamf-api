<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Exceptions;

/**
 * Thrown when the Jamf Pro API returns HTTP 429 Too Many Requests.
 */
class RateLimitException extends JamfException {}
