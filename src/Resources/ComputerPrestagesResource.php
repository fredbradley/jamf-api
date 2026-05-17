<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Data\Common\HistoryNote;
use Cranleigh\JamfApi\Data\Prestage\ComputerPrestage;
use Cranleigh\JamfApi\Pagination\Page;
use Cranleigh\JamfApi\Resources\Concerns\HasHistory;

/**
 * Computer prestages — automated device enrollment profiles for Mac computers.
 *
 * Required privilege: Read Computer PreStages / Update Computer PreStages /
 *                     Create Computer PreStages / Delete Computer PreStages
 */
class ComputerPrestagesResource extends AbstractResource
{
    use HasHistory;

    /** @var string Current prestage ID (set when scoping to a specific prestage). */
    private string $currentId = '';

    /**
     * List all computer prestages.
     *
     * @param  int  $page  Zero-based page index.
     * @param  int  $pageSize  Results per page.
     * @param  list<string>  $sort  Sort fields.
     * @return Page<ComputerPrestage>
     */
    public function list(int $page = 0, int $pageSize = 100, array $sort = []): Page
    {
        $response = $this->http->get('/v2/computer-prestages', $this->buildQuery([
            'page' => $page,
            'page-size' => $pageSize,
            'sort' => $sort ?: null,
        ]));

        return new Page(
            results: array_map(ComputerPrestage::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize: $pageSize,
        );
    }

    /**
     * Retrieve a specific computer prestage by ID.
     */
    public function find(string $id): ComputerPrestage
    {
        return ComputerPrestage::fromArray(
            $this->http->get("/v2/computer-prestages/{$id}")->json()
        );
    }

    /**
     * Create a new computer prestage.
     *
     * @param  array<string,mixed>  $data
     */
    public function create(array $data): ComputerPrestage
    {
        return ComputerPrestage::fromArray($this->http->post('/v2/computer-prestages', $data)->json());
    }

    /**
     * Update a computer prestage (full replacement).
     *
     * @param  array<string,mixed>  $data
     */
    public function update(string $id, array $data): ComputerPrestage
    {
        return ComputerPrestage::fromArray(
            $this->http->put("/v2/computer-prestages/{$id}", $data)->json()
        );
    }

    /**
     * Delete a computer prestage by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v2/computer-prestages/{$id}");
    }

    /**
     * Retrieve the scope (assigned serial numbers) for a computer prestage.
     *
     * @return array<string,mixed>
     */
    public function scope(string $id): array
    {
        return $this->http->get("/v2/computer-prestages/{$id}/scope")->json();
    }

    /**
     * Replace the entire scope of a computer prestage.
     *
     * @param  list<string>  $serialNumbers  Device serial numbers to assign.
     * @param  int  $versionLock  Current version lock from the prestage record.
     */
    public function replaceScope(string $id, array $serialNumbers, int $versionLock): array
    {
        return $this->http->put("/v2/computer-prestages/{$id}/scope", [
            'serialNumbers' => $serialNumbers,
            'versionLock' => $versionLock,
        ])->json();
    }

    /**
     * Add serial numbers to the scope of a computer prestage.
     *
     * @param  list<string>  $serialNumbers
     */
    public function addToScope(string $id, array $serialNumbers, int $versionLock): array
    {
        return $this->http->post("/v2/computer-prestages/{$id}/scope", [
            'serialNumbers' => $serialNumbers,
            'versionLock' => $versionLock,
        ])->json();
    }

    /**
     * Remove serial numbers from the scope of a computer prestage.
     *
     * @param  list<string>  $serialNumbers
     */
    public function removeFromScope(string $id, array $serialNumbers, int $versionLock): array
    {
        return $this->http->post("/v2/computer-prestages/{$id}/scope/delete-multiple", [
            'serialNumbers' => $serialNumbers,
            'versionLock' => $versionLock,
        ])->json();
    }

    /**
     * Retrieve history for a computer prestage.
     *
     * Shortcut that sets the current prestage ID and delegates to the HasHistory trait.
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
        return "/v2/computer-prestages/{$this->currentId}/history";
    }
}
