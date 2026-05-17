<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Data\MobileDevice\MobileDeviceDetail;
use Cranleigh\JamfApi\Data\MobileDevice\MobileDeviceSummary;
use Cranleigh\JamfApi\Pagination\Page;

/**
 * Mobile device inventory — query and manage iOS, iPadOS, tvOS, and watchOS devices.
 *
 * Required privilege: Read Mobile Devices
 */
class MobileDevicesResource extends AbstractResource
{
    /**
     * List mobile devices with optional filtering and sorting.
     *
     * @param  int           $page      Zero-based page index.
     * @param  int           $pageSize  Results per page.
     * @param  list<string>  $sort      Sort fields, e.g. ['name:asc'].
     * @param  string|null   $filter    RSQL filter string.
     * @return Page<MobileDeviceSummary>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v2/mobile-devices', $this->buildQuery([
            'page'      => $page,
            'page-size' => $pageSize,
            'sort'      => $sort ?: null,
            'filter'    => $filter,
        ]));

        return new Page(
            results:    array_map(MobileDeviceSummary::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize:   $pageSize,
        );
    }

    /**
     * Retrieve a specific mobile device by ID.
     */
    public function find(string $id): MobileDeviceSummary
    {
        return MobileDeviceSummary::fromArray($this->http->get("/v2/mobile-devices/{$id}")->json());
    }

    /**
     * Retrieve full inventory detail for a mobile device.
     */
    public function detail(string $id): MobileDeviceDetail
    {
        return MobileDeviceDetail::fromArray(
            $this->http->get("/v2/mobile-devices/{$id}/detail")->json()
        );
    }

    /**
     * Update a mobile device record (partial update via PATCH).
     *
     * @param  array<string,mixed> $data  Fields to update.
     */
    public function patch(string $id, array $data): MobileDeviceSummary
    {
        return MobileDeviceSummary::fromArray(
            $this->http->patch("/v2/mobile-devices/{$id}", $data)->json()
        );
    }
}
