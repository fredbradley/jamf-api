<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Resources\Concerns\HasHistory;

/**
 * Onboarding configuration — control the onboarding flow for new device users.
 *
 * Required privilege: Read Onboarding / Update Onboarding
 */
class OnboardingResource extends AbstractResource
{
    use HasHistory;

    /**
     * Retrieve the current onboarding configuration.
     *
     * @return array<string,mixed>
     */
    public function get(): array
    {
        return $this->http->get('/v3/onboarding')->json();
    }

    /**
     * Update the onboarding configuration.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function save(array $data): array
    {
        return $this->http->put('/v3/onboarding', $data)->json();
    }

    protected function historyPath(): string
    {
        return '/v1/onboarding/history';
    }
}
