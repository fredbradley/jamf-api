<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Exceptions;

/**
 * Thrown when the Jamf Pro API returns a 5xx Server Error.
 */
class ServerException extends JamfException {}
