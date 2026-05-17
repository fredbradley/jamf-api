<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Data\Patch;

/**
 * A Jamf Pro patch management software title.
 */
readonly class PatchTitle
{
    public function __construct(
        /** Unique identifier. */
        public string $id,
        /** Software title name. */
        public string $name,
        /** Whether the title is enabled for patch management. */
        public bool $enabled,
        /** The patch source type. */
        public ?string $sourceType,
        /** The name of the patch source. */
        public ?string $sourceName,
        /** Publisher/vendor name. */
        public ?string $publisher,
        /** Application name. */
        public ?string $appName,
        /** Latest available version. */
        public ?string $latestVersion,
        /** ISO 8601 timestamp of the last patch data update. */
        public ?string $lastUpdated,
    ) {}

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id:            (string) ($data['id'] ?? ''),
            name:          $data['name'] ?? '',
            enabled:       (bool) ($data['enabled'] ?? false),
            sourceType:    $data['sourceType'] ?? null,
            sourceName:    $data['sourceName'] ?? null,
            publisher:     $data['publisher'] ?? null,
            appName:       $data['appName'] ?? null,
            latestVersion: $data['latestVersion'] ?? null,
            lastUpdated:   $data['lastUpdated'] ?? null,
        );
    }
}
