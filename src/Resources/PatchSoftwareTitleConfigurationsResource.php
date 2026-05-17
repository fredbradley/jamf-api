<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Data\Common\HistoryNote;
use Cranleigh\JamfApi\Data\Patch\PatchSoftwareTitleConfiguration;
use Cranleigh\JamfApi\Pagination\Page;
use Cranleigh\JamfApi\Resources\Concerns\HasHistory;

/**
 * Patch software title configurations — the deployable patch policies.
 *
 * Each configuration targets a specific software title and defines the
 * deployment settings, notifications, and scope.
 *
 * Required privilege: Read Patch Management / Update Patch Management /
 *                     Create Patch Management / Delete Patch Management
 */
class PatchSoftwareTitleConfigurationsResource extends AbstractResource
{
    use HasHistory;

    private string $currentId = '';

    /**
     * List all patch software title configurations.
     *
     * @param  int           $page
     * @param  int           $pageSize
     * @param  list<string>  $sort
     * @param  string|null   $filter
     * @return Page<PatchSoftwareTitleConfiguration>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v2/patch-software-title-configurations', $this->buildQuery([
            'page'      => $page,
            'page-size' => $pageSize,
            'sort'      => $sort ?: null,
            'filter'    => $filter,
        ]));

        return new Page(
            results:    array_map(PatchSoftwareTitleConfiguration::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize:   $pageSize,
        );
    }

    /**
     * Retrieve a specific patch software title configuration.
     */
    public function find(string $id): PatchSoftwareTitleConfiguration
    {
        return PatchSoftwareTitleConfiguration::fromArray(
            $this->http->get("/v2/patch-software-title-configurations/{$id}")->json()
        );
    }

    /**
     * Create a new patch software title configuration.
     *
     * @param  array<string,mixed> $data
     */
    public function create(array $data): PatchSoftwareTitleConfiguration
    {
        return PatchSoftwareTitleConfiguration::fromArray(
            $this->http->post('/v2/patch-software-title-configurations', $data)->json()
        );
    }

    /**
     * Update a patch software title configuration (full replacement).
     *
     * @param  array<string,mixed> $data
     */
    public function update(string $id, array $data): PatchSoftwareTitleConfiguration
    {
        return PatchSoftwareTitleConfiguration::fromArray(
            $this->http->put("/v2/patch-software-title-configurations/{$id}", $data)->json()
        );
    }

    /**
     * Delete a patch software title configuration.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v2/patch-software-title-configurations/{$id}");
    }

    /**
     * Retrieve the dashboard data for a patch software title configuration.
     *
     * @return array<string,mixed>
     */
    public function dashboard(string $id): array
    {
        return $this->http->get("/v2/patch-software-title-configurations/{$id}/dashboard")->json();
    }

    /**
     * Retrieve the patch definitions for a patch software title configuration.
     *
     * @return array<string,mixed>
     */
    public function definitions(string $id): array
    {
        return $this->http->get("/v2/patch-software-title-configurations/{$id}/definitions")->json();
    }

    /**
     * Export a patch report for a configuration as CSV.
     *
     * @param  string|null  $filter  RSQL filter for the exported records.
     */
    public function exportReport(string $id, ?string $filter = null): string
    {
        return $this->http->post(
            "/v2/patch-software-title-configurations/{$id}/export-patch-report",
            $this->buildQuery(['filter' => $filter])
        )->body();
    }

    /**
     * Retrieve history for a specific configuration.
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
        return "/v2/patch-software-title-configurations/{$this->currentId}/history";
    }
}
