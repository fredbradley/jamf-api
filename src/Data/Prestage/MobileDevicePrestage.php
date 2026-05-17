<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Data\Prestage;

/**
 * A Jamf Pro mobile device prestage (automated device enrollment profile).
 */
readonly class MobileDevicePrestage
{
    public function __construct(
        /** Unique identifier. */
        public string $id,
        /** Display name. */
        public string $displayName,
        /** Whether this is the default prestage. */
        public bool $defaultPrestage,
        /** Whether location information is required during enrollment. */
        public bool $requireAuthentication,
        /** Whether to prevent Activation Lock. */
        public bool $preventActivationLock,
        /** Whether to enable device-based Activation Lock. */
        public bool $enableDeviceBasedActivationLock,
        /** Device enrollment program instance ID. */
        public ?string $deviceEnrollmentProgramInstanceId,
        /** Whether to skip the enrollment setup. */
        public bool $skipSetupItems,
        /** Site ID. */
        public ?string $siteId,
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
            defaultPrestage: (bool) ($data['defaultPrestage'] ?? false),
            requireAuthentication: (bool) ($data['requireAuthentication'] ?? false),
            preventActivationLock: (bool) ($data['preventActivationLock'] ?? false),
            enableDeviceBasedActivationLock: (bool) ($data['enableDeviceBasedActivationLock'] ?? false),
            deviceEnrollmentProgramInstanceId: isset($data['deviceEnrollmentProgramInstanceId'])
                ? (string) $data['deviceEnrollmentProgramInstanceId']
                : null,
            skipSetupItems: (bool) ($data['skipSetupItems'] ?? false),
            siteId: isset($data['siteId']) ? (string) $data['siteId'] : null,
            versionLock: (int) ($data['versionLock'] ?? 0),
            modificationDate: $data['modificationDate'] ?? null,
        );
    }
}
