<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Data\Common\HistoryNote;
use Cranleigh\JamfApi\Pagination\Page;
use Cranleigh\JamfApi\Resources\Concerns\HasHistory;

/**
 * Inventory preload — pre-populate device inventory data before enrollment.
 *
 * Supports CSV bulk import and individual record management.
 *
 * Required privilege: Read Inventory Preload Records / Update Inventory Preload Records /
 *                     Create Inventory Preload Records / Delete Inventory Preload Records
 */
class InventoryPreloadResource extends AbstractResource
{
    use HasHistory;

    /**
     * List all inventory preload records.
     *
     * @param  int           $page
     * @param  int           $pageSize
     * @param  list<string>  $sort
     * @param  string|null   $filter
     * @return Page<array<string,mixed>>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v2/inventory-preload/records', $this->buildQuery([
            'page'      => $page,
            'page-size' => $pageSize,
            'sort'      => $sort ?: null,
            'filter'    => $filter,
        ]));

        return new Page(
            results:    $response->json('results', []),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize:   $pageSize,
        );
    }

    /**
     * Retrieve a specific inventory preload record by ID.
     *
     * @return array<string,mixed>
     */
    public function find(string $id): array
    {
        return $this->http->get("/v2/inventory-preload/records/{$id}")->json();
    }

    /**
     * Create a new inventory preload record.
     *
     * @param  array<string,mixed> $data  Record data including serialNumber, deviceType, etc.
     * @return array<string,mixed>
     */
    public function create(array $data): array
    {
        return $this->http->post('/v2/inventory-preload/records', $data)->json();
    }

    /**
     * Update an inventory preload record (full replacement).
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function update(string $id, array $data): array
    {
        return $this->http->put("/v2/inventory-preload/records/{$id}", $data)->json();
    }

    /**
     * Delete an inventory preload record by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v2/inventory-preload/records/{$id}");
    }

    /**
     * Delete all inventory preload records.
     */
    public function deleteAll(): void
    {
        $this->http->delete('/v2/inventory-preload/records');
    }

    /**
     * Download the CSV template for bulk import.
     *
     * Returns the raw CSV content as a string.
     */
    public function csvTemplate(): string
    {
        return $this->http->get('/v2/inventory-preload/csv-template')->body();
    }

    /**
     * Import inventory preload records from a CSV string.
     *
     * @param  string  $csv  CSV content conforming to the template format.
     * @return array<string,mixed>  Import result with counts of created/updated/failed records.
     */
    public function importCsv(string $csv): array
    {
        return $this->http->postMultipart('/v2/inventory-preload/csv', [
            ['name' => 'file', 'contents' => $csv, 'filename' => 'preload.csv'],
        ])->json();
    }

    protected function historyPath(): string
    {
        return '/v2/inventory-preload/history';
    }
}
