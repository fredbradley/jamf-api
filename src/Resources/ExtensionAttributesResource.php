<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Pagination\Page;

/**
 * Extension attributes — custom inventory fields for computers and mobile devices.
 *
 * Required privilege: Read Computer Extension Attributes / Read Mobile Device Extension Attributes
 */
class ExtensionAttributesResource extends AbstractResource
{
    /**
     * List computer extension attribute definitions.
     *
     * @param  int           $page
     * @param  int           $pageSize
     * @param  list<string>  $sort
     * @param  string|null   $filter
     * @return Page<array<string,mixed>>
     */
    public function listComputer(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v1/computer-extension-attributes', $this->buildQuery([
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
     * Retrieve a specific computer extension attribute by ID.
     *
     * @return array<string,mixed>
     */
    public function findComputer(string $id): array
    {
        return $this->http->get("/v1/computer-extension-attributes/{$id}")->json();
    }

    /**
     * Create a computer extension attribute.
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function createComputer(array $data): array
    {
        return $this->http->post('/v1/computer-extension-attributes', $data)->json();
    }

    /**
     * Update a computer extension attribute (full replacement).
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function updateComputer(string $id, array $data): array
    {
        return $this->http->put("/v1/computer-extension-attributes/{$id}", $data)->json();
    }

    /**
     * Partially update a computer extension attribute.
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function patchComputer(string $id, array $data): array
    {
        return $this->http->patch("/v1/computer-extension-attributes/{$id}", $data)->json();
    }

    /**
     * Delete a computer extension attribute by ID.
     */
    public function deleteComputer(string $id): void
    {
        $this->http->delete("/v1/computer-extension-attributes/{$id}");
    }

    /**
     * List mobile device extension attribute definitions.
     *
     * @return Page<array<string,mixed>>
     */
    public function listMobileDevice(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v1/mobile-device-extension-attributes', $this->buildQuery([
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
     * Retrieve a specific mobile device extension attribute by ID.
     *
     * @return array<string,mixed>
     */
    public function findMobileDevice(string $id): array
    {
        return $this->http->get("/v1/mobile-device-extension-attributes/{$id}")->json();
    }

    /**
     * Create a mobile device extension attribute.
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function createMobileDevice(array $data): array
    {
        return $this->http->post('/v1/mobile-device-extension-attributes', $data)->json();
    }

    /**
     * Update a mobile device extension attribute (full replacement).
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function updateMobileDevice(string $id, array $data): array
    {
        return $this->http->put("/v1/mobile-device-extension-attributes/{$id}", $data)->json();
    }

    /**
     * Delete a mobile device extension attribute by ID.
     */
    public function deleteMobileDevice(string $id): void
    {
        $this->http->delete("/v1/mobile-device-extension-attributes/{$id}");
    }
}
