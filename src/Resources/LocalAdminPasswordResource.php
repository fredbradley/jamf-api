<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Pagination\Page;

/**
 * Local administrator password (LAPS) management.
 *
 * View and rotate local admin account passwords managed by Jamf Pro LAPS.
 *
 * Required privilege: Read Local Admin Password / Update Local Admin Password
 */
class LocalAdminPasswordResource extends AbstractResource
{
    /**
     * Retrieve the LAPS global settings.
     *
     * @return array<string,mixed>
     */
    public function settings(): array
    {
        return $this->http->get('/v2/local-admin-password')->json();
    }

    /**
     * Update the LAPS global settings.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function saveSettings(array $data): array
    {
        return $this->http->put('/v2/local-admin-password', $data)->json();
    }

    /**
     * Retrieve the current local admin password for a managed device.
     *
     * @param  string  $clientManagementId  The device's client management ID.
     * @param  string  $username  The local admin account username.
     * @return array<string,mixed>
     */
    public function getPassword(string $clientManagementId, string $username): array
    {
        return $this->http->get(
            "/v2/local-admin-password/{$clientManagementId}/account/{$username}/password"
        )->json();
    }

    /**
     * List all LAPS accounts for a managed device.
     *
     * @return list<array<string,mixed>>
     */
    public function accounts(string $clientManagementId): array
    {
        return $this->http->get("/v2/local-admin-password/{$clientManagementId}/accounts")
            ->json('accounts', []);
    }

    /**
     * List all LAPS accounts across all managed devices.
     *
     * @param  list<string>  $sort
     * @return Page<array<string,mixed>>
     */
    public function allAccounts(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v2/local-admin-password/accounts', $this->buildQuery([
            'page' => $page,
            'page-size' => $pageSize,
            'sort' => $sort ?: null,
            'filter' => $filter,
        ]));

        return new Page(
            results: $response->json('results', []),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize: $pageSize,
        );
    }

    /**
     * Rotate (regenerate) the local admin password for a managed device.
     */
    public function rotate(string $clientManagementId, string $username): void
    {
        $this->http->post("/v2/local-admin-password/{$clientManagementId}/account/{$username}/rotate");
    }

    /**
     * Retrieve the password audit history for a specific LAPS account.
     *
     * @return Page<array<string,mixed>>
     */
    public function passwordHistory(
        string $clientManagementId,
        string $username,
        int $page = 0,
        int $pageSize = 100,
    ): Page {
        $response = $this->http->get(
            "/v2/local-admin-password/{$clientManagementId}/account/{$username}/password-history",
            $this->buildQuery(['page' => $page, 'page-size' => $pageSize])
        );

        return new Page(
            results: $response->json('results', []),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize: $pageSize,
        );
    }
}
