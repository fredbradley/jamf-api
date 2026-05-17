<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Pagination\Page;

/**
 * Enrollment customization — customize the Setup Assistant workflow shown
 * during device enrollment.
 *
 * Required privilege: Read Enrollment Customizations / Update Enrollment Customizations /
 *                     Create Enrollment Customizations / Delete Enrollment Customizations
 */
class EnrollmentCustomizationResource extends AbstractResource
{
    /**
     * List all enrollment customizations.
     *
     * @param  int           $page
     * @param  int           $pageSize
     * @param  list<string>  $sort
     * @param  string|null   $filter
     * @return Page<array<string,mixed>>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v2/enrollment-customizations', $this->buildQuery([
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
     * Retrieve a specific enrollment customization by ID.
     *
     * @return array<string,mixed>
     */
    public function find(string $id): array
    {
        return $this->http->get("/v2/enrollment-customizations/{$id}")->json();
    }

    /**
     * Create a new enrollment customization.
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function create(array $data): array
    {
        return $this->http->post('/v2/enrollment-customizations', $data)->json();
    }

    /**
     * Update an enrollment customization (full replacement).
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function update(string $id, array $data): array
    {
        return $this->http->put("/v2/enrollment-customizations/{$id}", $data)->json();
    }

    /**
     * Delete an enrollment customization by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v2/enrollment-customizations/{$id}");
    }
}
