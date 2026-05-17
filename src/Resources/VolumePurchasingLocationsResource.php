<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Resources;

use FredBradley\JamfApi\Data\Common\HistoryNote;
use FredBradley\JamfApi\Pagination\Page;
use FredBradley\JamfApi\Resources\Concerns\HasHistory;

/**
 * Volume Purchasing (VPP) locations — Apple Business Manager / School Manager
 * content token connections.
 *
 * Required privilege: Read Volume Purchasing Locations / Update Volume Purchasing Locations /
 *                     Create Volume Purchasing Locations / Delete Volume Purchasing Locations
 */
class VolumePurchasingLocationsResource extends AbstractResource
{
    use HasHistory;

    private string $currentId = '';

    /**
     * List all Volume Purchasing locations.
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
        $response = $this->http->get('/v1/volume-purchasing-locations', $this->buildQuery([
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
     * Retrieve a Volume Purchasing location by ID.
     *
     * @return array<string,mixed>
     */
    public function find(string $id): array
    {
        return $this->http->get("/v1/volume-purchasing-locations/{$id}")->json();
    }

    /**
     * Create a new Volume Purchasing location.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function create(array $data): array
    {
        return $this->http->post('/v1/volume-purchasing-locations', $data)->json();
    }

    /**
     * Update a Volume Purchasing location (full replacement).
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function update(string $id, array $data): array
    {
        return $this->http->put("/v1/volume-purchasing-locations/{$id}", $data)->json();
    }

    /**
     * Delete a Volume Purchasing location.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v1/volume-purchasing-locations/{$id}");
    }

    /**
     * Revoke all licenses associated with this Volume Purchasing location.
     */
    public function revokeLicenses(string $id): void
    {
        $this->http->post("/v1/volume-purchasing-locations/{$id}/revoke-licenses");
    }

    /**
     * Retrieve history for a specific Volume Purchasing location.
     *
     * @return Page<HistoryNote>
     */
    public function historyFor(
        string $id,
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $this->currentId = $id;

        return $this->history($page, $pageSize, $sort, $filter);
    }

    protected function historyPath(): string
    {
        return "/v1/volume-purchasing-locations/{$this->currentId}/history";
    }
}
