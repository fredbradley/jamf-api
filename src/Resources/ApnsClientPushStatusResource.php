<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Resources;

use FredBradley\JamfApi\Pagination\Page;

/**
 * APNS client push status — find devices with disabled push notifications.
 *
 * Required privilege: Read Computers / Read Mobile Devices
 */
class ApnsClientPushStatusResource extends AbstractResource
{
    /**
     * Search for managed clients with disabled APNS push notifications.
     *
     * @param  list<string>  $sort
     * @return Page<array<string,mixed>>
     */
    public function search(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v1/apns-client-push-status', $this->buildQuery([
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
}
