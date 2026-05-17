<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Resources\Concerns\HasHistory;

/**
 * Jamf Connect configuration and deployment.
 *
 * Required privilege: Read Jamf Connect / Update Jamf Connect
 */
class JamfConnectResource extends AbstractResource
{
    use HasHistory;

    /**
     * Retrieve the current Jamf Connect settings.
     *
     * @return array<string,mixed>
     */
    public function get(): array
    {
        return $this->http->get('/v2/jamf-connect')->json();
    }

    /**
     * Update Jamf Connect settings.
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function save(array $data): array
    {
        return $this->http->put('/v2/jamf-connect', $data)->json();
    }

    /**
     * Retrieve Jamf Connect deployment configurations.
     *
     * @return array<string,mixed>
     */
    public function deployments(): array
    {
        return $this->http->get('/v1/jamf-connect/deployments')->json();
    }

    /**
     * Update Jamf Connect deployment configurations.
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function updateDeployments(array $data): array
    {
        return $this->http->put('/v1/jamf-connect/deployments', $data)->json();
    }

    /**
     * Retrieve the deployment task queue for Jamf Connect.
     *
     * @return array<string,mixed>
     */
    public function deploymentTasks(): array
    {
        return $this->http->get('/v1/jamf-connect/deployments/tasks')->json();
    }

    protected function historyPath(): string
    {
        return '/v1/jamf-connect/history';
    }
}
