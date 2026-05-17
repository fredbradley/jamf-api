<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Pagination\Page;

/**
 * Managed Software Updates — deploy and track OS/software update plans.
 *
 * Required privilege: Read Managed Software Updates / Create Managed Software Updates /
 *                     Delete Managed Software Updates
 */
class ManagedSoftwareUpdatesResource extends AbstractResource
{
    /**
     * Retrieve available managed software updates.
     *
     * @return array<string,mixed>
     */
    public function availableUpdates(): array
    {
        return $this->http->get('/v1/managed-software-updates/available-updates')->json();
    }

    /**
     * List managed software update plans.
     *
     * @param  list<string>  $sort
     * @return Page<array<string,mixed>>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v2/managed-software-updates/plans', $this->buildQuery([
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
     * Retrieve a specific managed software update plan by ID.
     *
     * @return array<string,mixed>
     */
    public function find(string $id): array
    {
        return $this->http->get("/v2/managed-software-updates/plans/{$id}")->json();
    }

    /**
     * Create a new managed software update plan.
     *
     * @param  array<string,mixed>  $data  Plan configuration.
     * @return array<string,mixed>
     */
    public function create(array $data): array
    {
        return $this->http->post('/v2/managed-software-updates/plans', $data)->json();
    }

    /**
     * Delete a managed software update plan.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v2/managed-software-updates/plans/{$id}");
    }

    /**
     * Create an update plan targeting a computer group.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function createGroupPlan(array $data): array
    {
        return $this->http->post('/v2/managed-software-updates/plans/group', $data)->json();
    }
}
