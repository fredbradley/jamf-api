<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Resources\Concerns\HasHistory;

/**
 * Jamf Pro cache settings.
 *
 * Required privilege: Read Cache Settings / Update Cache Settings
 */
class CacheSettingsResource extends AbstractResource
{
    use HasHistory;

    /**
     * Retrieve the current cache settings.
     *
     * @return array<string,mixed>
     */
    public function get(): array
    {
        return $this->http->get('/v1/cache-settings')->json();
    }

    /**
     * Update cache settings.
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function save(array $data): array
    {
        return $this->http->put('/v1/cache-settings', $data)->json();
    }

    protected function historyPath(): string
    {
        return '/v1/cache-settings/history';
    }
}
