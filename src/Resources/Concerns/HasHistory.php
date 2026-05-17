<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources\Concerns;

use Cranleigh\JamfApi\Data\Common\HistoryNote;
use Cranleigh\JamfApi\Pagination\Page;

/**
 * Provides history listing and note-adding for resources that support it.
 *
 * The using class must implement {@see historyPath()} returning the base API
 * path for history operations, e.g. '/v1/scripts/{id}/history'.
 */
trait HasHistory
{
    /**
     * Retrieve history entries for this resource.
     *
     * @param  int            $page      Zero-based page index.
     * @param  int            $pageSize  Number of results per page.
     * @param  list<string>   $sort      Sort fields, e.g. ['date:desc', 'username:asc'].
     * @param  string|null    $filter    RSQL filter string, e.g. 'username=="admin"'.
     * @return Page<HistoryNote>
     */
    public function history(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get($this->historyPath(), $this->buildQuery([
            'page'      => $page,
            'page-size' => $pageSize,
            'sort'      => $sort ?: null,
            'filter'    => $filter,
        ]));

        return new Page(
            results:    array_map(HistoryNote::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize:   $pageSize,
        );
    }

    /**
     * Add a note to this resource's history.
     */
    public function addHistoryNote(string $note): HistoryNote
    {
        $response = $this->http->post($this->historyPath() . '/notes', ['note' => $note]);

        return HistoryNote::fromArray($response->json());
    }

    /**
     * The API path for this resource's history endpoint.
     *
     * e.g. '/v1/scripts/42/history'
     */
    abstract protected function historyPath(): string;
}
