<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Pagination\Page;

/**
 * Computer groups — smart and static computer groups.
 *
 * Required privilege: Read Computer Groups
 */
class ComputerGroupsResource extends AbstractResource
{
    /**
     * List all computer groups (smart and static).
     *
     * @param  int           $page      Zero-based page index.
     * @param  int           $pageSize  Results per page.
     * @param  list<string>  $sort      Sort fields, e.g. ['name:asc'].
     * @param  string|null   $filter    RSQL filter string.
     * @return Page<array<string,mixed>>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v1/computer-groups', $this->buildQuery([
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
     * Retrieve a computer group by ID.
     *
     * @return array<string,mixed>
     */
    public function find(string $id): array
    {
        return $this->http->get("/v2/computer-groups/{$id}")->json();
    }

    /**
     * List all smart computer groups.
     *
     * @return Page<array<string,mixed>>
     */
    public function smart(int $page = 0, int $pageSize = 100): Page
    {
        $response = $this->http->get('/v2/smart-computer-groups', $this->buildQuery([
            'page'      => $page,
            'page-size' => $pageSize,
        ]));

        return new Page(
            results:    $response->json('results', []),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize:   $pageSize,
        );
    }

    /**
     * List all static computer groups.
     *
     * @return Page<array<string,mixed>>
     */
    public function static(int $page = 0, int $pageSize = 100): Page
    {
        $response = $this->http->get('/v2/static-computer-groups', $this->buildQuery([
            'page'      => $page,
            'page-size' => $pageSize,
        ]));

        return new Page(
            results:    $response->json('results', []),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize:   $pageSize,
        );
    }
}
