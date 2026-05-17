<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Data\Patch\PatchTitle;
use Cranleigh\JamfApi\Pagination\Page;

/**
 * Patch management software titles — the catalogue of patchable software.
 *
 * Required privilege: Read Patch Management Software Titles
 */
class PatchTitlesResource extends AbstractResource
{
    /**
     * List all available patch management software titles.
     *
     * @param  int           $page
     * @param  int           $pageSize
     * @param  list<string>  $sort
     * @param  string|null   $filter
     * @return Page<PatchTitle>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v3/patch-titles', $this->buildQuery([
            'page'      => $page,
            'page-size' => $pageSize,
            'sort'      => $sort ?: null,
            'filter'    => $filter,
        ]));

        return new Page(
            results:    array_map(PatchTitle::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize:   $pageSize,
        );
    }

    /**
     * Retrieve a specific patch title by ID.
     */
    public function find(string $id): PatchTitle
    {
        return PatchTitle::fromArray($this->http->get("/v3/patch-titles/{$id}")->json());
    }

    /**
     * Retrieve the individual patches (versions) for a patch title.
     *
     * @return Page<array<string,mixed>>
     */
    public function patches(string $id, int $page = 0, int $pageSize = 100): Page
    {
        $response = $this->http->get("/v3/patch-titles/{$id}/patches", $this->buildQuery([
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
