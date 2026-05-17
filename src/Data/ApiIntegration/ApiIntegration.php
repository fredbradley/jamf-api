<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Data\ApiIntegration;

/**
 * A Jamf Pro API integration (OAuth client).
 */
readonly class ApiIntegration
{
    public function __construct(
        /** Unique identifier. */
        public string $id,
        /** Display name for this integration. */
        public string $displayName,
        /** Whether this integration is enabled. */
        public bool $enabled,
        /** The access token lifetime in seconds. */
        public int $accessTokenLifetimeSeconds,
        /**
         * IDs of the API roles assigned to this integration.
         *
         * @var list<string>
         */
        public array $apiRoleIds,
        /** OAuth client ID, available after creation. */
        public ?string $clientId,
        /** ISO 8601 timestamp when the integration was created. */
        public ?string $authorizationCode,
    ) {}

    /**
     * @param  array<string,mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) ($data['id'] ?? ''),
            displayName: $data['displayName'] ?? '',
            enabled: (bool) ($data['enabled'] ?? true),
            accessTokenLifetimeSeconds: (int) ($data['accessTokenLifetimeSeconds'] ?? 1800),
            apiRoleIds: $data['apiRoleIds'] ?? [],
            clientId: $data['clientId'] ?? null,
            authorizationCode: $data['authorizationCode'] ?? null,
        );
    }
}
