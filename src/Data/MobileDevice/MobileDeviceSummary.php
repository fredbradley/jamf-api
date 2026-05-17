<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Data\MobileDevice;

/**
 * A lightweight mobile device record returned by list/search endpoints.
 */
readonly class MobileDeviceSummary
{
    public function __construct(
        /** Unique identifier used by the Jamf Pro API. */
        public string $id,
        /** Universally unique device identifier. */
        public string $udid,
        /** Device name. */
        public ?string $name,
        /** Hardware serial number. */
        public ?string $serialNumber,
        /** Wi-Fi MAC address. */
        public ?string $wifiMacAddress,
        /** Bluetooth MAC address. */
        public ?string $bluetoothMacAddress,
        /** Asset tag label. */
        public ?string $assetTag,
        /** Device model name. */
        public ?string $model,
        /** Apple model identifier string. */
        public ?string $modelIdentifier,
        /** Operating system version. */
        public ?string $osVersion,
        /** OS build string. */
        public ?string $osBuild,
        /** Phone number, for devices that support it. */
        public ?string $phoneNumber,
        /** ICCID (SIM card identifier). */
        public ?string $iccid,
        /** IMEI number. */
        public ?string $imei,
        /** Whether the device is MDM-managed. */
        public bool $managed,
        /** Whether the device is supervised. */
        public bool $supervised,
        /** Username of the assigned user. */
        public ?string $username,
        /** Real name of the assigned user. */
        public ?string $realName,
        /** Email of the assigned user. */
        public ?string $email,
        /** ISO 8601 timestamp of the last inventory update. */
        public ?string $lastInventoryUpdateDate,
        /** Site name the device belongs to. */
        public ?string $site,
    ) {}

    /**
     * @param  array<string,mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) ($data['id'] ?? ''),
            udid: $data['udid'] ?? '',
            name: $data['name'] ?? null,
            serialNumber: $data['serialNumber'] ?? null,
            wifiMacAddress: $data['wifiMacAddress'] ?? null,
            bluetoothMacAddress: $data['bluetoothMacAddress'] ?? null,
            assetTag: $data['assetTag'] ?? null,
            model: $data['model'] ?? null,
            modelIdentifier: $data['modelIdentifier'] ?? null,
            osVersion: $data['osVersion'] ?? null,
            osBuild: $data['osBuild'] ?? null,
            phoneNumber: $data['phoneNumber'] ?? null,
            iccid: $data['iccid'] ?? null,
            imei: $data['imei'] ?? null,
            managed: (bool) ($data['managed'] ?? false),
            supervised: (bool) ($data['supervised'] ?? false),
            username: $data['username'] ?? null,
            realName: $data['realName'] ?? null,
            email: $data['email'] ?? null,
            lastInventoryUpdateDate: $data['lastInventoryUpdateDate'] ?? null,
            site: $data['site']['name'] ?? $data['site'] ?? null,
        );
    }
}
