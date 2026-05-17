<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Resources;

use FredBradley\JamfApi\Data\Account\AccountGroup;
use FredBradley\JamfApi\Pagination\Page;

/**
 * Jamf Pro account groups.
 *
 * Required privilege: Read Account Groups
 */
class AccountGroupsResource extends AbstractResource
{
    /**
     * List all account groups with optional filtering and sorting.
     *
     * @param  int  $page  Zero-based page index.
     * @param  int  $pageSize  Results per page.
     * @param  list<string>  $sort  Sort fields, e.g. ['name:asc'].
     * @param  string|null  $filter  RSQL filter string.
     * @return Page<AccountGroup>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v1/account-groups', $this->buildQuery([
            'page' => $page,
            'page-size' => $pageSize,
            'sort' => $sort ?: null,
            'filter' => $filter,
        ]));

        return new Page(
            results: array_map(AccountGroup::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize: $pageSize,
        );
    }

    /**
     * Retrieve a specific account group by ID.
     */
    public function find(string $id): AccountGroup
    {
        return AccountGroup::fromArray($this->http->get("/v1/account-groups/{$id}")->json());
    }
}
