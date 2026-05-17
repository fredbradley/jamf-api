<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Data\Common;

/**
 * A Jamf Pro site reference embedded in other resource objects.
 */
readonly class Site
{
    public function __construct(
        public string $id,
        public string $name,
    ) {}

    /**
     * @param  array<string,mixed>|null  $data
     */
    public static function fromArray(?array $data): ?self
    {
        if ($data === null || $data === []) {
            return null;
        }

        return new self(
            id: (string) ($data['id'] ?? ''),
            name: $data['name'] ?? '',
        );
    }
}
