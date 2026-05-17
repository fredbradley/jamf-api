<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Data\Department;

/**
 * A Jamf Pro department.
 */
readonly class Department
{
    public function __construct(
        /** Unique identifier. */
        public string $id,
        /** Department name. */
        public string $name,
    ) {}

    /**
     * @param  array<string,mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) ($data['id'] ?? ''),
            name: $data['name'] ?? '',
        );
    }
}
