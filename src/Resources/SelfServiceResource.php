<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

/**
 * Self Service application settings.
 *
 * Required privilege: Read Self Service / Update Self Service
 */
class SelfServiceResource extends AbstractResource
{
    /**
     * Retrieve the current Self Service settings.
     *
     * @return array<string,mixed>
     */
    public function get(): array
    {
        return $this->http->get('/v2/self-service/settings')->json();
    }

    /**
     * Update Self Service settings.
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function save(array $data): array
    {
        return $this->http->put('/v2/self-service/settings', $data)->json();
    }
}
