<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Data\Common;

/**
 * A single history note entry returned by resource history endpoints.
 */
readonly class HistoryNote
{
    public function __construct(
        /** Unique identifier for this history entry. */
        public int $id,
        /** The username of the user who created this entry. */
        public string $username,
        /** ISO 8601 timestamp when this entry was created. */
        public string $date,
        /** Short human-readable note or action description. */
        public string $note,
        /** Detailed description of what changed, if available. */
        public ?string $details,
    ) {}

    /**
     * @param  array<string,mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['id'] ?? 0),
            username: $data['username'] ?? '',
            date: $data['date'] ?? '',
            note: $data['note'] ?? '',
            details: $data['details'] ?? null,
        );
    }
}
