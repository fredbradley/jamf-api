<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Pagination\Page;

/**
 * Mobile device managed apps.
 *
 * Required privilege: Read Mobile Device Applications
 */
class MobileDeviceAppsResource extends AbstractResource
{
    /**
     * List all mobile device managed apps.
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
        $response = $this->http->get('/v1/mobile-device-apps', $this->buildQuery([
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
     * Retrieve a specific mobile device app by ID.
     *
     * @return array<string,mixed>
     */
    public function find(string $id): array
    {
        return $this->http->get("/v1/mobile-device-apps/{$id}")->json();
    }

    /**
     * Create a new mobile device managed app record.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function create(array $data): array
    {
        return $this->http->post('/v1/mobile-device-apps', $data)->json();
    }

    /**
     * Update a mobile device managed app (full replacement).
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function update(string $id, array $data): array
    {
        return $this->http->put("/v1/mobile-device-apps/{$id}", $data)->json();
    }

    /**
     * Delete a mobile device managed app by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v1/mobile-device-apps/{$id}");
    }
}
