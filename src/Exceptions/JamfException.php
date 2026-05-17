<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Exceptions;

use RuntimeException;

/**
 * Base exception for all Jamf API errors.
 */
class JamfException extends RuntimeException
{
    public function __construct(
        string $message = '',
        private readonly int $httpStatus = 0,
        private readonly ?array $errors = null,
    ) {
        parent::__construct($message);
    }

    /**
     * The HTTP status code returned by the Jamf Pro API.
     */
    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }

    /**
     * Any structured error details returned in the response body.
     *
     * @return array<string,mixed>|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }
}
