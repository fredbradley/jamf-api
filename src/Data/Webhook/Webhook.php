<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Data\Webhook;

/**
 * A Jamf Pro webhook configuration.
 */
readonly class Webhook
{
    public function __construct(
        /** Unique identifier. */
        public string $id,
        /** Display name for this webhook. */
        public string $name,
        /** Whether the webhook is currently active. */
        public bool $enabled,
        /** The URL that Jamf Pro will POST to when the event fires. */
        public string $url,
        /** Content type: 'JSON' or 'XML'. */
        public string $contentType,
        /** The Jamf Pro event that triggers this webhook. */
        public string $event,
        /** Connection timeout in seconds. */
        public ?int $connectionTimeout,
        /** Read timeout in seconds. */
        public ?int $readTimeout,
        /** Whether to verify the SSL certificate of the endpoint. */
        public bool $enableSslCertificateVerification,
        /** Smart group event trigger: 'ADDED', 'REMOVED', or 'BOTH'. */
        public ?string $smartGroupTrigger,
    ) {}

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id:                              (string) ($data['id'] ?? ''),
            name:                            $data['name'] ?? '',
            enabled:                         (bool) ($data['enabled'] ?? false),
            url:                             $data['url'] ?? '',
            contentType:                     $data['contentType'] ?? 'JSON',
            event:                           $data['event'] ?? '',
            connectionTimeout:               isset($data['connectionTimeout']) ? (int) $data['connectionTimeout'] : null,
            readTimeout:                     isset($data['readTimeout']) ? (int) $data['readTimeout'] : null,
            enableSslCertificateVerification: (bool) ($data['enableSslCertificateVerification'] ?? true),
            smartGroupTrigger:               $data['smartGroupTrigger'] ?? null,
        );
    }
}
