<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Pagination\Page;

/**
 * Mobile device groups — smart and static mobile device groups.
 *
 * Required privilege: Read Mobile Device Groups
 */
class MobileDeviceGroupsResource extends AbstractResource
{
    /**
     * Retrieve a mobile device group by ID.
     *
     * @return array<string,mixed>
     */
    public function find(string $id): array
    {
        return $this->http->get("/v2/mobile-device-groups/{$id}")->json();
    }

    /**
     * List all smart mobile device groups.
     *
     * @return Page<array<string,mixed>>
     */
    public function smart(int $page = 0, int $pageSize = 100): Page
    {
        $response = $this->http->get('/v2/smart-mobile-device-groups', $this->buildQuery([
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
     * List all static mobile device groups.
     *
     * @return Page<array<string,mixed>>
     */
    public function static(int $page = 0, int $pageSize = 100): Page
    {
        $response = $this->http->get('/v2/static-mobile-device-groups', $this->buildQuery([
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
}
