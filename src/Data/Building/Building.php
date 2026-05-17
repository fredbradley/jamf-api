<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Data\Building;

/**
 * A Jamf Pro building.
 */
readonly class Building
{
    public function __construct(
        /** Unique identifier. */
        public string $id,
        /** Building name. */
        public string $name,
        /** Street address line 1. */
        public ?string $streetAddress1,
        /** Street address line 2. */
        public ?string $streetAddress2,
        /** City. */
        public ?string $city,
        /** State or province. */
        public ?string $stateProvince,
        /** Zip or postal code. */
        public ?string $zipPostalCode,
        /** Country. */
        public ?string $country,
    ) {}

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id:             (string) ($data['id'] ?? ''),
            name:           $data['name'] ?? '',
            streetAddress1: $data['streetAddress1'] ?? null,
            streetAddress2: $data['streetAddress2'] ?? null,
            city:           $data['city'] ?? null,
            stateProvince:  $data['stateProvince'] ?? null,
            zipPostalCode:  $data['zipPostalCode'] ?? null,
            country:        $data['country'] ?? null,
        );
    }
}
