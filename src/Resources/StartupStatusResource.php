<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

/**
 * Jamf Pro startup status — check whether the server has finished starting up.
 *
 * This endpoint does not require authentication and can be used to poll
 * until the Jamf Pro server is ready to accept API requests.
 */
class StartupStatusResource extends AbstractResource
{
    /**
     * Retrieve the current startup/initialization status.
     *
     * Returns information about the current startup step and whether
     * the application is ready.
     *
     * @return array{
     *   startupStatus: string,
     *   percentComplete: int,
     *   steps: list<array{name: string, status: string}>
     * }
     */
    public function get(): array
    {
        return $this->http->get('/startup-status')->json();
    }
}
