<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Data\ApiRole;

/**
 * A Jamf Pro API role that can be assigned to API integrations.
 */
readonly class ApiRole
{
    public function __construct(
        /** Unique identifier. */
        public string $id,
        /** Display name for this API role. */
        public string $displayName,
        /**
         * List of privilege strings assigned to this role.
         * @var list<string>
         */
        public array $privileges,
    ) {}

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id:          (string) ($data['id'] ?? ''),
            displayName: $data['displayName'] ?? '',
            privileges:  $data['privileges'] ?? [],
        );
    }
}
