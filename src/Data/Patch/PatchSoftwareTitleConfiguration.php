<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Data\Patch;

/**
 * A Jamf Pro patch software title configuration.
 */
readonly class PatchSoftwareTitleConfiguration
{
    public function __construct(
        /** Unique identifier. */
        public string $id,
        /** Display name for this configuration. */
        public string $displayName,
        /** The patch title ID this configuration targets. */
        public string $softwareTitleId,
        /** Category ID. */
        public ?string $categoryId,
        /** Site ID. */
        public ?string $siteId,
        /** ID of the distribution point used for this configuration. */
        public ?string $distributionMethod,
        /** The patch notification settings. */
        public ?array $notificationSettings,
        /** The grace period settings. */
        public ?array $gracePeriodSettings,
        /** Version lock (for concurrency control). */
        public int $versionLock,
        /** ISO 8601 timestamp of last modification. */
        public ?string $modificationDate,
    ) {}

    /**
     * @param  array<string,mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) ($data['id'] ?? ''),
            displayName: $data['displayName'] ?? '',
            softwareTitleId: (string) ($data['softwareTitleId'] ?? ''),
            categoryId: isset($data['categoryId']) ? (string) $data['categoryId'] : null,
            siteId: isset($data['siteId']) ? (string) $data['siteId'] : null,
            distributionMethod: $data['distributionMethod'] ?? null,
            notificationSettings: $data['notificationSettings'] ?? null,
            gracePeriodSettings: $data['gracePeriodSettings'] ?? null,
            versionLock: (int) ($data['versionLock'] ?? 0),
            modificationDate: $data['modificationDate'] ?? null,
        );
    }
}
