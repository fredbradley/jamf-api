<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Pagination\Page;

/**
 * Advanced mobile device searches — saved complex searches against device inventory.
 *
 * Required privilege: Read Advanced Mobile Device Searches / Update Advanced Mobile Device Searches /
 *                     Create Advanced Mobile Device Searches / Delete Advanced Mobile Device Searches
 */
class AdvancedMobileDeviceSearchesResource extends AbstractResource
{
    /**
     * List all advanced mobile device searches.
     *
     * @return Page<array<string,mixed>>
     */
    public function list(int $page = 0, int $pageSize = 100): Page
    {
        $response = $this->http->get('/v1/advanced-mobile-device-searches', $this->buildQuery([
            'page' => $page,
            'page-size' => $pageSize,
        ]));

        return new Page(
            results: $response->json('results', []),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize: $pageSize,
        );
    }

    /**
     * Retrieve a specific advanced search by ID.
     *
     * @return array<string,mixed>
     */
    public function find(string $id): array
    {
        return $this->http->get("/v1/advanced-mobile-device-searches/{$id}")->json();
    }

    /**
     * Retrieve the available search criteria choices.
     *
     * @return array<string,mixed>
     */
    public function choices(): array
    {
        return $this->http->get('/v1/advanced-mobile-device-searches/choices')->json();
    }

    /**
     * Create a new advanced mobile device search.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function create(array $data): array
    {
        return $this->http->post('/v1/advanced-mobile-device-searches', $data)->json();
    }

    /**
     * Update an advanced mobile device search (full replacement).
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function update(string $id, array $data): array
    {
        return $this->http->put("/v1/advanced-mobile-device-searches/{$id}", $data)->json();
    }

    /**
     * Delete an advanced mobile device search by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v1/advanced-mobile-device-searches/{$id}");
    }

    /**
     * Delete multiple advanced mobile device searches.
     *
     * @param  list<string>  $ids
     */
    public function deleteMultiple(array $ids): void
    {
        $this->http->post('/v1/advanced-mobile-device-searches/delete-multiple', ['ids' => $ids]);
    }
}
