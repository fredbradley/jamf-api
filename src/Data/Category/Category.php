<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Data\Category;

/**
 * A Jamf Pro category.
 */
readonly class Category
{
    public function __construct(
        /** Unique identifier. */
        public string $id,
        /** Category display name. */
        public string $name,
        /** Self-Service display priority. */
        public int $priority,
    ) {}

    /**
     * @param  array<string,mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) ($data['id'] ?? ''),
            name: $data['name'] ?? '',
            priority: (int) ($data['priority'] ?? 9),
        );
    }
}
