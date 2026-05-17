<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Data\Building\Building;
use Cranleigh\JamfApi\Data\Common\HistoryNote;
use Cranleigh\JamfApi\Pagination\Page;
use Cranleigh\JamfApi\Resources\Concerns\HasCsvExport;
use Cranleigh\JamfApi\Resources\Concerns\HasHistory;

/**
 * Jamf Pro buildings.
 *
 * Required privilege: Read Buildings / Update Buildings / Create Buildings / Delete Buildings
 */
class BuildingsResource extends AbstractResource
{
    use HasHistory;
    use HasCsvExport;

    /**
     * List all buildings.
     *
     * @param  int           $page
     * @param  int           $pageSize
     * @param  list<string>  $sort
     * @param  string|null   $filter
     * @return Page<Building>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v1/buildings', $this->buildQuery([
            'page'      => $page,
            'page-size' => $pageSize,
            'sort'      => $sort ?: null,
            'filter'    => $filter,
        ]));

        return new Page(
            results:    array_map(Building::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize:   $pageSize,
        );
    }

    /**
     * Retrieve a specific building by ID.
     */
    public function find(string $id): Building
    {
        return Building::fromArray($this->http->get("/v1/buildings/{$id}")->json());
    }

    /**
     * Create a new building.
     *
     * @param  array<string,mixed> $data  Building properties (name, streetAddress1, city, etc.).
     */
    public function create(array $data): Building
    {
        return Building::fromArray($this->http->post('/v1/buildings', $data)->json());
    }

    /**
     * Update a building (full replacement).
     *
     * @param  array<string,mixed> $data
     */
    public function update(string $id, array $data): Building
    {
        return Building::fromArray($this->http->put("/v1/buildings/{$id}", $data)->json());
    }

    /**
     * Delete a building by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v1/buildings/{$id}");
    }

    /**
     * Delete multiple buildings.
     *
     * @param  list<string>  $ids
     */
    public function deleteMultiple(array $ids): void
    {
        $this->http->post('/v1/buildings/delete-multiple', ['ids' => $ids]);
    }

    protected function historyPath(): string
    {
        return '/v1/buildings/history';
    }

    protected function exportPath(): string
    {
        return '/v1/buildings/history/export';
    }
}
