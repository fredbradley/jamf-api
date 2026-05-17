<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Data\Common;

/**
 * A minimal response containing only an HREF link to the created/modified resource.
 *
 * Returned by some POST endpoints instead of the full resource object.
 */
readonly class HrefResponse
{
    public function __construct(
        /** The URL of the created or updated resource. */
        public string $href,
        /** The resource ID extracted from the href, if available. */
        public ?string $id,
    ) {}

    /**
     * @param  array<string,mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $href = $data['href'] ?? '';
        $id = null;

        if ($href !== '') {
            $parts = explode('/', $href);
            $id = end($parts) ?: null;
        }

        return new self(href: $href, id: $id);
    }
}
