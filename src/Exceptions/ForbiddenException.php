<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Exceptions;

/**
 * Thrown when the Jamf Pro API returns HTTP 403 Forbidden.
 *
 * The authenticated user does not have the required privilege for this operation.
 */
class ForbiddenException extends JamfException {}
