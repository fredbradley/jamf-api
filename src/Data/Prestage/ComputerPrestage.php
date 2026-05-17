<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Data\Prestage;

/**
 * A Jamf Pro computer prestage (automated device enrollment profile).
 */
readonly class ComputerPrestage
{
    public function __construct(
        /** Unique identifier. */
        public string $id,
        /** Display name. */
        public string $displayName,
        /** Whether this is the default prestage. */
        public bool $defaultPrestage,
        /** Whether enrollment is mandatory. */
        public bool $enrollmentSiteId,
        /** Whether to enable self-servicing during setup. */
        public bool $keepExistingAccountSettings,
        /** Whether location information is required during enrollment. */
        public bool $requireAuthentication,
        /** The authentication prompt message shown during enrollment. */
        public ?string $authenticationPrompt,
        /** Whether to prevent Login During Setup Assistant. */
        public bool $preventActivationLock,
        /** Whether supervision is enabled. */
        public bool $enableDeviceBasedActivationLock,
        /** Device enrollment program instance ID. */
        public ?string $deviceEnrollmentProgramInstanceId,
        /** Site ID. */
        public ?string $siteId,
        /** Version lock (for concurrency control). */
        public int $versionLock,
        /** The management account settings. */
        public ?array $managementAccount,
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
            enrollmentSiteId: (bool) ($data['enrollmentSiteId'] ?? false),
            keepExistingAccountSettings: (bool) ($data['keepExistingAccountSettings'] ?? false),
            requireAuthentication: (bool) ($data['requireAuthentication'] ?? false),
            authenticationPrompt: $data['authenticationPrompt'] ?? null,
            preventActivationLock: (bool) ($data['preventActivationLock'] ?? false),
            enableDeviceBasedActivationLock: (bool) ($data['enableDeviceBasedActivationLock'] ?? false),
            deviceEnrollmentProgramInstanceId: isset($data['deviceEnrollmentProgramInstanceId'])
                ? (string) $data['deviceEnrollmentProgramInstanceId']
                : null,
            siteId: isset($data['siteId']) ? (string) $data['siteId'] : null,
            versionLock: (int) ($data['versionLock'] ?? 0),
            managementAccount: $data['managementAccount'] ?? null,
            modificationDate: $data['modificationDate'] ?? null,
        );
    }
}
