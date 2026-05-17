<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Pagination\Page;

/**
 * Advanced user content searches — saved complex searches for user-side content.
 *
 * Required privilege: Read Advanced User Content Searches / Update / Create / Delete
 */
class AdvancedUserContentSearchesResource extends AbstractResource
{
    /**
     * List all advanced user content searches.
     *
     * @return Page<array<string,mixed>>
     */
    public function list(int $page = 0, int $pageSize = 100): Page
    {
        $response = $this->http->get('/v1/advanced-user-content-searches', $this->buildQuery([
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
     * Retrieve a specific advanced user content search by ID.
     *
     * @return array<string,mixed>
     */
    public function find(string $id): array
    {
        return $this->http->get("/v1/advanced-user-content-searches/{$id}")->json();
    }

    /**
     * Create a new advanced user content search.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function create(array $data): array
    {
        return $this->http->post('/v1/advanced-user-content-searches', $data)->json();
    }

    /**
     * Update an advanced user content search (full replacement).
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function update(string $id, array $data): array
    {
        return $this->http->put("/v1/advanced-user-content-searches/{$id}", $data)->json();
    }

    /**
     * Delete an advanced user content search by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v1/advanced-user-content-searches/{$id}");
    }
}
