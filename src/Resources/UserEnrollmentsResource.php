<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Pagination\Page;

/**
 * User-initiated enrollment sessions.
 *
 * Required privilege: Read User Initiated Enrollment / Delete User Initiated Enrollment
 */
class UserEnrollmentsResource extends AbstractResource
{
    /**
     * List all user enrollment records.
     *
     * @param  list<string>  $sort
     * @return Page<array<string,mixed>>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v1/user-enrollments', $this->buildQuery([
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

    /**
     * Retrieve a specific user enrollment by ID.
     *
     * @return array<string,mixed>
     */
    public function find(string $id): array
    {
        return $this->http->get("/v1/user-enrollments/{$id}")->json();
    }

    /**
     * Delete a user enrollment.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v1/user-enrollments/{$id}");
    }

    /**
     * List devices for a user enrollment.
     *
     * @return Page<array<string,mixed>>
     */
    public function devices(string $id, int $page = 0, int $pageSize = 100): Page
    {
        $response = $this->http->get("/v1/user-enrollments/{$id}/devices", $this->buildQuery([
            'page' => $page,
            'page-size' => $pageSize,
        ]));

        return new Page(
            results: $response->json('results', []),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize: $pageSize,
        );
    }
}
