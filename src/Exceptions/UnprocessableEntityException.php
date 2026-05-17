<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Exceptions;

/**
 * Thrown when the Jamf Pro API returns HTTP 422 Unprocessable Entity.
 *
 * The request body failed validation. Check {@see getErrors()} for field-level details.
 */
class UnprocessableEntityException extends JamfException {}
