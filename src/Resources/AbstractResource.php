<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\JamfHttpClient;

/**
 * Base class for all Jamf Pro API resource wrappers.
 */
abstract class AbstractResource
{
    public function __construct(
        protected readonly JamfHttpClient $http,
    ) {}

    /**
     * Build a query parameter array, stripping null, empty string, and empty
     * array values so they are not sent to the API.
     *
     * @param  array<string,mixed> $params
     * @return array<string,mixed>
     */
    protected function buildQuery(array $params): array
    {
        return array_filter(
            $params,
            static fn (mixed $v): bool => $v !== null && $v !== '' && $v !== [],
        );
    }
}
