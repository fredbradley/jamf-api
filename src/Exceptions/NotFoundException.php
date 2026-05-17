<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Exceptions;

/**
 * Thrown when the Jamf Pro API returns HTTP 404 Not Found.
 */
class NotFoundException extends JamfException {}
