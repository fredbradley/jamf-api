<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Data\ApiRole\ApiRole;
use Cranleigh\JamfApi\Pagination\Page;

/**
 * Jamf Pro API roles.
 *
 * API roles define a named set of privileges that can be assigned to API integrations.
 *
 * Required privilege: Read API Roles / Update API Roles / Create API Roles / Delete API Roles
 */
class ApiRolesResource extends AbstractResource
{
    /**
     * List all API roles.
     *
     * @param  int  $page  Zero-based page index.
     * @param  int  $pageSize  Results per page.
     * @param  list<string>  $sort  Sort fields, e.g. ['displayName:asc'].
     * @param  string|null  $filter  RSQL filter string.
     * @return Page<ApiRole>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v1/api-roles', $this->buildQuery([
            'page' => $page,
            'page-size' => $pageSize,
            'sort' => $sort ?: null,
            'filter' => $filter,
        ]));

        return new Page(
            results: array_map(ApiRole::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize: $pageSize,
        );
    }

    /**
     * Retrieve a specific API role by ID.
     */
    public function find(string $id): ApiRole
    {
        return ApiRole::fromArray($this->http->get("/v1/api-roles/{$id}")->json());
    }

    /**
     * Create a new API role.
     *
     * @param  string  $displayName  Display name for the role.
     * @param  list<string>  $privileges  Privilege strings to assign.
     */
    public function create(string $displayName, array $privileges): ApiRole
    {
        return ApiRole::fromArray(
            $this->http->post('/v1/api-roles', [
                'displayName' => $displayName,
                'privileges' => $privileges,
            ])->json()
        );
    }

    /**
     * Update an existing API role (full replacement).
     *
     * @param  string  $displayName  New display name.
     * @param  list<string>  $privileges  New set of privilege strings.
     */
    public function update(string $id, string $displayName, array $privileges): ApiRole
    {
        return ApiRole::fromArray(
            $this->http->put("/v1/api-roles/{$id}", [
                'displayName' => $displayName,
                'privileges' => $privileges,
            ])->json()
        );
    }

    /**
     * Delete an API role by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v1/api-roles/{$id}");
    }
}
