<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Resources;

use FredBradley\JamfApi\Data\Common\HistoryNote;
use FredBradley\JamfApi\Data\Package\JamfPackage;
use FredBradley\JamfApi\Pagination\Page;
use FredBradley\JamfApi\Resources\Concerns\HasHistory;

/**
 * Jamf Pro packages.
 *
 * Required privilege: Read Packages / Update Packages / Create Packages / Delete Packages
 */
class PackagesResource extends AbstractResource
{
    use HasHistory;

    private string $currentId = '';

    /**
     * List all packages.
     *
     * @param  list<string>  $sort
     * @return Page<JamfPackage>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v1/packages', $this->buildQuery([
            'page' => $page,
            'page-size' => $pageSize,
            'sort' => $sort ?: null,
            'filter' => $filter,
        ]));

        return new Page(
            results: array_map(JamfPackage::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize: $pageSize,
        );
    }

    /**
     * Retrieve a specific package by ID.
     */
    public function find(string $id): JamfPackage
    {
        return JamfPackage::fromArray($this->http->get("/v1/packages/{$id}")->json());
    }

    /**
     * Create a new package record.
     *
     * Note: This creates the metadata record. The actual package file must be
     * uploaded separately to the distribution point.
     *
     * @param  array<string,mixed>  $data
     */
    public function create(array $data): JamfPackage
    {
        return JamfPackage::fromArray($this->http->post('/v1/packages', $data)->json());
    }

    /**
     * Update an existing package (full replacement).
     *
     * @param  array<string,mixed>  $data
     */
    public function update(string $id, array $data): JamfPackage
    {
        return JamfPackage::fromArray($this->http->put("/v1/packages/{$id}", $data)->json());
    }

    /**
     * Delete a package by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v1/packages/{$id}");
    }

    /**
     * Delete multiple packages.
     *
     * @param  list<string>  $ids
     */
    public function deleteMultiple(array $ids): void
    {
        $this->http->post('/v1/packages/delete-multiple', ['ids' => $ids]);
    }

    /**
     * Retrieve history for a specific package.
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
        return "/v1/packages/{$this->currentId}/history";
    }
}
