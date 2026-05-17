<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Data\Computer;

/**
 * A lightweight computer record returned by list/search endpoints.
 */
readonly class ComputerSummary
{
    public function __construct(
        /** Unique identifier used by the Jamf Pro API. */
        public string $id,
        /** Universally unique device identifier. */
        public string $udid,
        /** Computer name as configured in Jamf Pro. */
        public ?string $name,
        /** Hardware serial number. */
        public ?string $serialNumber,
        /** Asset tag label. */
        public ?string $assetTag,
        /** ISO 8601 timestamp of the last check-in. */
        public ?string $lastContactTime,
        /** ISO 8601 timestamp of the last inventory report. */
        public ?string $lastReportDate,
        /** ISO 8601 timestamp when the device last enrolled. */
        public ?string $lastEnrolledDate,
        /** Operating system version string, e.g. "14.4.1". */
        public ?string $osVersion,
        /** Primary IP address at the time of last check-in. */
        public ?string $ipAddress,
        /** Username of the assigned user. */
        public ?string $username,
        /** Full name of the assigned user. */
        public ?string $realName,
        /** Email address of the assigned user. */
        public ?string $email,
        /** Department the device is assigned to. */
        public ?string $department,
        /** Building the device is assigned to. */
        public ?string $building,
        /** Primary wired MAC address. */
        public ?string $macAddress,
        /** Hardware model name. */
        public ?string $model,
        /** Apple model identifier string. */
        public ?string $modelIdentifier,
        /** Whether the device is MDM-managed. */
        public bool $managed,
        /** Whether the device is supervised. */
        public bool $supervised,
        /** Site name the device belongs to. */
        public ?string $site,
    ) {}

    /**
     * @param  array<string,mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $general = $data['general'] ?? $data;
        $hardware = $data['hardware'] ?? [];
        $userInfo = $data['userAndLocation'] ?? [];

        return new self(
            id: (string) ($data['id'] ?? ''),
            udid: $data['udid'] ?? '',
            name: $general['name'] ?? $data['name'] ?? null,
            serialNumber: $hardware['serialNumber'] ?? $data['serialNumber'] ?? null,
            assetTag: $general['assetTag'] ?? $data['assetTag'] ?? null,
            lastContactTime: $general['lastContactTime'] ?? $data['lastContactTime'] ?? null,
            lastReportDate: $general['reportDate'] ?? $data['lastReportDate'] ?? null,
            lastEnrolledDate: $general['lastEnrolledDate'] ?? $data['lastEnrolledDate'] ?? null,
            osVersion: $data['operatingSystemVersion'] ?? $hardware['osVersion'] ?? null,
            ipAddress: $general['lastIpAddress'] ?? $data['primaryIpAddress'] ?? null,
            username: $userInfo['username'] ?? $data['username'] ?? null,
            realName: $userInfo['realName'] ?? $data['realName'] ?? null,
            email: $userInfo['email'] ?? $data['email'] ?? null,
            department: $userInfo['department'] ?? $data['department'] ?? null,
            building: $userInfo['building'] ?? $data['building'] ?? null,
            macAddress: $hardware['macAddress'] ?? $data['macAddress'] ?? null,
            model: $hardware['model'] ?? $data['model'] ?? null,
            modelIdentifier: $hardware['modelIdentifier'] ?? $data['modelIdentifier'] ?? null,
            managed: (bool) ($general['remoteManagement']['managed'] ?? $data['managed'] ?? false),
            supervised: (bool) ($general['supervised'] ?? $data['supervised'] ?? false),
            site: $general['site']['name'] ?? $data['site'] ?? null,
        );
    }
}
