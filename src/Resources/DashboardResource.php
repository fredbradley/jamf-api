<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Resources;

/**
 * Jamf Pro dashboard data.
 *
 * Returns summary counts and status information displayed on the Jamf Pro
 * dashboard, such as device counts, managed/unmanaged splits, etc.
 *
 * Required privilege: Read Dashboard
 */
class DashboardResource extends AbstractResource
{
    /**
     * Retrieve dashboard summary data.
     *
     * @return array<string,mixed>
     */
    public function get(): array
    {
        return $this->http->get('/v1/dashboard')->json();
    }
}
