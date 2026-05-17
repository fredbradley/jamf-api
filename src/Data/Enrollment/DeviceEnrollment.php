<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Data\Enrollment;

/**
 * An Apple Device Enrollment Program (DEP/ADE) instance configured in Jamf Pro.
 */
readonly class DeviceEnrollment
{
    public function __construct(
        /** Unique identifier. */
        public string $id,
        /** Display name for this enrollment instance. */
        public string $name,
        /** The supervisor organisation name (as shown in Apple Business Manager). */
        public ?string $supervisorName,
        /** The supervisor phone number. */
        public ?string $supervisorPhone,
        /** The supervisor email. */
        public ?string $supervisorEmail,
        /** The server name. */
        public ?string $serverName,
        /** Server UUID. */
        public ?string $serverUuid,
        /** Admin ID. */
        public ?string $adminId,
        /** Organisation name. */
        public ?string $orgName,
        /** Organisation phone number. */
        public ?string $orgPhone,
        /** Organisation address. */
        public ?string $orgAddress,
        /** Organisation address 2. */
        public ?string $orgAddress2,
        /** Organisation city. */
        public ?string $orgCity,
        /** Organisation state/province. */
        public ?string $orgStateOrProvince,
        /** Organisation ZIP/postal code. */
        public ?string $orgZipOrPostalCode,
        /** Organisation country. */
        public ?string $orgCountry,
        /** Token expiry date (ISO 8601). */
        public ?string $tokenExpirationDate,
        /** ISO 8601 timestamp of last sync. */
        public ?string $lastSyncDate,
    ) {}

    /**
     * @param  array<string,mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) ($data['id'] ?? ''),
            name: $data['name'] ?? '',
            supervisorName: $data['supervisorName'] ?? null,
            supervisorPhone: $data['supervisorPhone'] ?? null,
            supervisorEmail: $data['supervisorEmail'] ?? null,
            serverName: $data['serverName'] ?? null,
            serverUuid: $data['serverUuid'] ?? null,
            adminId: $data['adminId'] ?? null,
            orgName: $data['orgName'] ?? null,
            orgPhone: $data['orgPhone'] ?? null,
            orgAddress: $data['orgAddress'] ?? null,
            orgAddress2: $data['orgAddress2'] ?? null,
            orgCity: $data['orgCity'] ?? null,
            orgStateOrProvince: $data['orgStateOrProvince'] ?? null,
            orgZipOrPostalCode: $data['orgZipOrPostalCode'] ?? null,
            orgCountry: $data['orgCountry'] ?? null,
            tokenExpirationDate: $data['tokenExpirationDate'] ?? null,
            lastSyncDate: $data['lastSyncDate'] ?? null,
        );
    }
}
