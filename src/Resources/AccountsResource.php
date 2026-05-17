<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Data\Account\Account;
use Cranleigh\JamfApi\Pagination\Page;

/**
 * Jamf Pro user accounts.
 *
 * Required privilege: Read Accounts / Update Accounts / Create Accounts / Delete Accounts
 */
class AccountsResource extends AbstractResource
{
    /**
     * List all Jamf Pro user accounts with optional pagination, filtering, and sorting.
     *
     * @param  int  $page  Zero-based page index.
     * @param  int  $pageSize  Results per page (max 100).
     * @param  list<string>  $sort  Sort fields, e.g. ['username:asc', 'email:desc'].
     * @param  string|null  $filter  RSQL filter, e.g. 'username=="admin*"'.
     * @return Page<Account>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v1/accounts', $this->buildQuery([
            'page' => $page,
            'page-size' => $pageSize,
            'sort' => $sort ?: null,
            'filter' => $filter,
        ]));

        return new Page(
            results: array_map(Account::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize: $pageSize,
        );
    }

    /**
     * Retrieve a specific user account by ID.
     */
    public function find(string $id): Account
    {
        return Account::fromArray($this->http->get("/v1/accounts/{$id}")->json());
    }

    /**
     * Create a new user account.
     *
     * @param  array<string,mixed>  $data  Account properties.
     */
    public function create(array $data): Account
    {
        return Account::fromArray($this->http->post('/v1/accounts', $data)->json());
    }

    /**
     * Update an existing user account (full replacement).
     *
     * @param  array<string,mixed>  $data  Updated account properties.
     */
    public function update(string $id, array $data): Account
    {
        return Account::fromArray($this->http->put("/v1/accounts/{$id}", $data)->json());
    }

    /**
     * Delete a user account by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v1/accounts/{$id}");
    }
}
