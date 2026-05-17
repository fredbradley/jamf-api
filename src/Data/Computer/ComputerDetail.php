<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Data\Computer;

/**
 * Full computer record returned by the v2 computer detail endpoint.
 *
 * Sections that were not requested are null. Use ComputerInventoryResource::sections()
 * to request specific inventory sections.
 */
readonly class ComputerDetail
{
    public function __construct(
        public string $id,
        public string $udid,
        /** @var array<string,mixed>|null General section. */
        public ?array $general,
        /** @var array<string,mixed>|null Disk encryption section. */
        public ?array $diskEncryption,
        /** @var array<string,mixed>|null Purchasing section. */
        public ?array $purchasing,
        /** @var array<string,mixed>|null Applications section. */
        public ?array $applications,
        /** @var array<string,mixed>|null Storage section. */
        public ?array $storage,
        /** @var array<string,mixed>|null User and location section. */
        public ?array $userAndLocation,
        /** @var array<string,mixed>|null Configuration profiles section. */
        public ?array $configurationProfiles,
        /** @var array<string,mixed>|null Printers section. */
        public ?array $printers,
        /** @var array<string,mixed>|null Services section. */
        public ?array $services,
        /** @var array<string,mixed>|null Hardware section. */
        public ?array $hardware,
        /** @var array<string,mixed>|null Local user accounts section. */
        public ?array $localUserAccounts,
        /** @var array<string,mixed>|null Certificates section. */
        public ?array $certificates,
        /** @var array<string,mixed>|null Attachments section. */
        public ?array $attachments,
        /** @var array<string,mixed>|null Plugins section. */
        public ?array $plugins,
        /** @var array<string,mixed>|null Package receipts section. */
        public ?array $packageReceipts,
        /** @var array<string,mixed>|null Fonts section. */
        public ?array $fonts,
        /** @var array<string,mixed>|null Security section. */
        public ?array $security,
        /** @var array<string,mixed>|null Operating system section. */
        public ?array $operatingSystem,
        /** @var array<string,mixed>|null Licensed software section. */
        public ?array $licensedSoftware,
        /** @var array<string,mixed>|null iBeacons section. */
        public ?array $ibeacons,
        /** @var array<string,mixed>|null Software updates section. */
        public ?array $softwareUpdates,
        /** @var array<string,mixed>|null Extension attributes section. */
        public ?array $extensionAttributes,
        /** @var array<string,mixed>|null Content caching section. */
        public ?array $contentCaching,
        /** @var array<string,mixed>|null Group memberships section. */
        public ?array $groupMemberships,
    ) {}

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id:                    (string) ($data['id'] ?? ''),
            udid:                  $data['udid'] ?? '',
            general:               $data['general'] ?? null,
            diskEncryption:        $data['diskEncryption'] ?? null,
            purchasing:            $data['purchasing'] ?? null,
            applications:          $data['applications'] ?? null,
            storage:               $data['storage'] ?? null,
            userAndLocation:       $data['userAndLocation'] ?? null,
            configurationProfiles: $data['configurationProfiles'] ?? null,
            printers:              $data['printers'] ?? null,
            services:              $data['services'] ?? null,
            hardware:              $data['hardware'] ?? null,
            localUserAccounts:     $data['localUserAccounts'] ?? null,
            certificates:          $data['certificates'] ?? null,
            attachments:           $data['attachments'] ?? null,
            plugins:               $data['plugins'] ?? null,
            packageReceipts:       $data['packageReceipts'] ?? null,
            fonts:                 $data['fonts'] ?? null,
            security:              $data['security'] ?? null,
            operatingSystem:       $data['operatingSystem'] ?? null,
            licensedSoftware:      $data['licensedSoftware'] ?? null,
            ibeacons:              $data['ibeacons'] ?? null,
            softwareUpdates:       $data['softwareUpdates'] ?? null,
            extensionAttributes:   $data['extensionAttributes'] ?? null,
            contentCaching:        $data['contentCaching'] ?? null,
            groupMemberships:      $data['groupMemberships'] ?? null,
        );
    }
}
