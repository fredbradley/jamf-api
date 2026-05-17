<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Resources\Concerns\HasHistory;

/**
 * Computer check-in settings — configure how often managed computers check in.
 *
 * Required privilege: Read Computer Check-In / Update Computer Check-In
 */
class CheckInResource extends AbstractResource
{
    use HasHistory;

    /**
     * Retrieve the current computer check-in settings.
     *
     * @return array<string,mixed>
     */
    public function get(): array
    {
        return $this->http->get('/v2/check-in')->json();
    }

    /**
     * Update the computer check-in settings.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function save(array $data): array
    {
        return $this->http->put('/v2/check-in', $data)->json();
    }

    protected function historyPath(): string
    {
        return '/v1/check-in/history';
    }
}
