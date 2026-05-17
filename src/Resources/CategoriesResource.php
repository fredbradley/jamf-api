<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Data\Category\Category;
use Cranleigh\JamfApi\Data\Common\HistoryNote;
use Cranleigh\JamfApi\Pagination\Page;
use Cranleigh\JamfApi\Resources\Concerns\HasHistory;

/**
 * Jamf Pro categories.
 *
 * Required privilege: Read Categories / Update Categories / Create Categories / Delete Categories
 */
class CategoriesResource extends AbstractResource
{
    use HasHistory;

    private string $currentId = '';

    /**
     * List all categories.
     *
     * @param  list<string>  $sort
     * @return Page<Category>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v1/categories', $this->buildQuery([
            'page' => $page,
            'page-size' => $pageSize,
            'sort' => $sort ?: null,
            'filter' => $filter,
        ]));

        return new Page(
            results: array_map(Category::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize: $pageSize,
        );
    }

    /**
     * Retrieve a specific category by ID.
     */
    public function find(string $id): Category
    {
        return Category::fromArray($this->http->get("/v1/categories/{$id}")->json());
    }

    /**
     * Create a new category.
     *
     * @param  string  $name  Category name.
     * @param  int  $priority  Display priority (default 9).
     */
    public function create(string $name, int $priority = 9): Category
    {
        return Category::fromArray(
            $this->http->post('/v1/categories', ['name' => $name, 'priority' => $priority])->json()
        );
    }

    /**
     * Update an existing category.
     */
    public function update(string $id, string $name, int $priority = 9): Category
    {
        return Category::fromArray(
            $this->http->put("/v1/categories/{$id}", ['name' => $name, 'priority' => $priority])->json()
        );
    }

    /**
     * Delete a category by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v1/categories/{$id}");
    }

    /**
     * Delete multiple categories.
     *
     * @param  list<string>  $ids
     */
    public function deleteMultiple(array $ids): void
    {
        $this->http->post('/v1/categories/delete-multiple', ['ids' => $ids]);
    }

    /**
     * Retrieve history for a specific category.
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
        return "/v1/categories/{$this->currentId}/history";
    }
}
