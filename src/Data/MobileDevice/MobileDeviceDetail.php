<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Data\MobileDevice;

/**
 * Full mobile device record including all inventory sections.
 */
readonly class MobileDeviceDetail
{
    public function __construct(
        public string $id,
        public string $udid,
        /** @var array<string,mixed>|null */
        public ?array $general,
        /** @var array<string,mixed>|null */
        public ?array $location,
        /** @var array<string,mixed>|null */
        public ?array $purchasing,
        /** @var array<string,mixed>|null */
        public ?array $applications,
        /** @var array<string,mixed>|null */
        public ?array $security,
        /** @var array<string,mixed>|null */
        public ?array $network,
        /** @var array<string,mixed>|null */
        public ?array $serviceSubscriptions,
        /** @var array<string,mixed>|null */
        public ?array $hardware,
        /** @var array<string,mixed>|null */
        public ?array $softwareUpdates,
        /** @var array<string,mixed>|null */
        public ?array $extensionAttributes,
        /** @var array<string,mixed>|null */
        public ?array $configurationProfiles,
        /** @var array<string,mixed>|null */
        public ?array $certificates,
        /** @var array<string,mixed>|null */
        public ?array $attachments,
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
            location:              $data['location'] ?? null,
            purchasing:            $data['purchasing'] ?? null,
            applications:          $data['applications'] ?? null,
            security:              $data['security'] ?? null,
            network:               $data['network'] ?? null,
            serviceSubscriptions:  $data['serviceSubscriptions'] ?? null,
            hardware:              $data['hardware'] ?? null,
            softwareUpdates:       $data['softwareUpdates'] ?? null,
            extensionAttributes:   $data['extensionAttributes'] ?? null,
            configurationProfiles: $data['configurationProfiles'] ?? null,
            certificates:          $data['certificates'] ?? null,
            attachments:           $data['attachments'] ?? null,
        );
    }
}
