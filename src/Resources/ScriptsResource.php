<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Resources;

use FredBradley\JamfApi\Data\Common\HistoryNote;
use FredBradley\JamfApi\Data\Script\Script;
use FredBradley\JamfApi\Pagination\Page;
use FredBradley\JamfApi\Resources\Concerns\HasHistory;

/**
 * Jamf Pro scripts.
 *
 * Required privilege: Read Scripts / Update Scripts / Create Scripts / Delete Scripts
 */
class ScriptsResource extends AbstractResource
{
    use HasHistory;

    private string $currentId = '';

    /**
     * List all scripts with optional filtering and sorting.
     *
     * @param  list<string>  $sort  e.g. ['name:asc'].
     * @param  string|null  $filter  RSQL filter, e.g. 'name=="Deploy*"'.
     * @return Page<Script>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v1/scripts', $this->buildQuery([
            'page' => $page,
            'page-size' => $pageSize,
            'sort' => $sort ?: null,
            'filter' => $filter,
        ]));

        return new Page(
            results: array_map(Script::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize: $pageSize,
        );
    }

    /**
     * Retrieve a specific script by ID.
     */
    public function find(string $id): Script
    {
        return Script::fromArray($this->http->get("/v1/scripts/{$id}")->json());
    }

    /**
     * Create a new script.
     *
     * @param  array<string,mixed>  $data  Script properties including 'name' and 'scriptContents'.
     */
    public function create(array $data): Script
    {
        return Script::fromArray($this->http->post('/v1/scripts', $data)->json());
    }

    /**
     * Update an existing script (full replacement).
     *
     * @param  array<string,mixed>  $data
     */
    public function update(string $id, array $data): Script
    {
        return Script::fromArray($this->http->put("/v1/scripts/{$id}", $data)->json());
    }

    /**
     * Delete a script by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v1/scripts/{$id}");
    }

    /**
     * Retrieve history for a specific script.
     *
     * @return Page<HistoryNote>
     */
    public function historyFor(
        string $id,
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $this->currentId = $id;

        return $this->history($page, $pageSize, $sort, $filter);
    }

    protected function historyPath(): string
    {
        return "/v1/scripts/{$this->currentId}/history";
    }
}
