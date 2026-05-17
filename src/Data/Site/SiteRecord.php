<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Data\Site;

/**
 * A Jamf Pro site returned by the sites list endpoint.
 */
readonly class SiteRecord
{
    public function __construct(
        /** Unique identifier. */
        public string $id,
        /** Site display name. */
        public string $name,
    ) {}

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id:   (string) ($data['id'] ?? ''),
            name: $data['name'] ?? '',
        );
    }
}
