<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Data\Common\HistoryNote;
use Cranleigh\JamfApi\Data\Department\Department;
use Cranleigh\JamfApi\Pagination\Page;
use Cranleigh\JamfApi\Resources\Concerns\HasCsvExport;
use Cranleigh\JamfApi\Resources\Concerns\HasHistory;

/**
 * Jamf Pro departments.
 *
 * Required privilege: Read Departments / Update Departments / Create Departments / Delete Departments
 */
class DepartmentsResource extends AbstractResource
{
    use HasHistory;
    use HasCsvExport;

    private string $currentId = '';

    /**
     * List all departments.
     *
     * @param  int           $page
     * @param  int           $pageSize
     * @param  list<string>  $sort
     * @param  string|null   $filter
     * @return Page<Department>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v1/departments', $this->buildQuery([
            'page'      => $page,
            'page-size' => $pageSize,
            'sort'      => $sort ?: null,
            'filter'    => $filter,
        ]));

        return new Page(
            results:    array_map(Department::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize:   $pageSize,
        );
    }

    /**
     * Retrieve a specific department by ID.
     */
    public function find(string $id): Department
    {
        return Department::fromArray($this->http->get("/v1/departments/{$id}")->json());
    }

    /**
     * Create a new department.
     */
    public function create(string $name): Department
    {
        return Department::fromArray($this->http->post('/v1/departments', ['name' => $name])->json());
    }

    /**
     * Update a department name.
     */
    public function update(string $id, string $name): Department
    {
        return Department::fromArray($this->http->put("/v1/departments/{$id}", ['name' => $name])->json());
    }

    /**
     * Delete a department by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v1/departments/{$id}");
    }

    /**
     * Delete multiple departments.
     *
     * @param  list<string>  $ids
     */
    public function deleteMultiple(array $ids): void
    {
        $this->http->post('/v1/departments/delete-multiple', ['ids' => $ids]);
    }

    /**
     * Retrieve global department history.
     *
     * @return Page<HistoryNote>
     */
    public function globalHistory(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        return $this->history($page, $pageSize, $sort, $filter);
    }

    protected function historyPath(): string
    {
        return '/v1/departments/history';
    }

    protected function exportPath(): string
    {
        return '/v1/departments/history/export';
    }
}
