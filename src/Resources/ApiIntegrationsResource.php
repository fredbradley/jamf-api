<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Resources;

use FredBradley\JamfApi\Data\ApiIntegration\ApiIntegration;
use FredBradley\JamfApi\Pagination\Page;

/**
 * Jamf Pro API integrations (OAuth clients).
 *
 * API integrations are OAuth 2.0 clients that consume the Jamf Pro API
 * using the client_credentials grant with assigned API roles.
 *
 * Required privilege: Read API Integrations / Update API Integrations /
 *                     Create API Integrations / Delete API Integrations
 */
class ApiIntegrationsResource extends AbstractResource
{
    /**
     * List all API integrations.
     *
     * @param  int  $page  Zero-based page index.
     * @param  int  $pageSize  Results per page.
     * @param  list<string>  $sort  Sort fields, e.g. ['displayName:asc'].
     * @param  string|null  $filter  RSQL filter string.
     * @return Page<ApiIntegration>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v1/api-integrations', $this->buildQuery([
            'page' => $page,
            'page-size' => $pageSize,
            'sort' => $sort ?: null,
            'filter' => $filter,
        ]));

        return new Page(
            results: array_map(ApiIntegration::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize: $pageSize,
        );
    }

    /**
     * Retrieve a specific API integration by ID.
     */
    public function find(string $id): ApiIntegration
    {
        return ApiIntegration::fromArray($this->http->get("/v1/api-integrations/{$id}")->json());
    }

    /**
     * Create a new API integration.
     *
     * @param  array<string,mixed>  $data  Integration properties (displayName, enabled, apiRoleIds, accessTokenLifetimeSeconds).
     */
    public function create(array $data): ApiIntegration
    {
        return ApiIntegration::fromArray($this->http->post('/v1/api-integrations', $data)->json());
    }

    /**
     * Update an existing API integration (full replacement).
     *
     * @param  array<string,mixed>  $data  Updated integration properties.
     */
    public function update(string $id, array $data): ApiIntegration
    {
        return ApiIntegration::fromArray($this->http->put("/v1/api-integrations/{$id}", $data)->json());
    }

    /**
     * Delete an API integration by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v1/api-integrations/{$id}");
    }

    /**
     * Generate new OAuth client credentials (client_id + client_secret) for an integration.
     *
     * The new client_secret is returned only once and cannot be retrieved again.
     *
     * @return array{clientId: string, clientSecret: string}
     */
    public function generateClientCredentials(string $id): array
    {
        return $this->http->post("/v1/api-integrations/{$id}/client-credentials")->json();
    }
}
