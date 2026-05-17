<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Resources;

use FredBradley\JamfApi\Data\Computer\ComputerDetail;
use FredBradley\JamfApi\Data\Computer\ComputerSummary;
use FredBradley\JamfApi\Pagination\Page;

/**
 * Computer inventory — the primary endpoint for querying and managing computer records.
 *
 * Uses the v2 inventory API which supports rich filtering, sorting, and
 * section-based detail retrieval.
 *
 * Required privilege: Read Computers
 */
class ComputerInventoryResource extends AbstractResource
{
    /**
     * List computers with optional filtering, sorting, and section selection.
     *
     * @param  int  $page  Zero-based page index.
     * @param  int  $pageSize  Results per page.
     * @param  list<string>  $sort  Sort fields, e.g. ['general.name:asc'].
     * @param  string|null  $filter  RSQL filter, e.g. 'general.name=="MacBook*"'.
     * @param  list<string>  $section  Inventory sections to include in the response.
     *                                 Default includes only general fields.
     *                                 Available sections: GENERAL, DISK_ENCRYPTION, PURCHASING,
     *                                 APPLICATIONS, STORAGE, USER_AND_LOCATION, CONFIGURATION_PROFILES,
     *                                 PRINTERS, SERVICES, HARDWARE, LOCAL_USER_ACCOUNTS, CERTIFICATES,
     *                                 ATTACHMENTS, PLUGINS, PACKAGE_RECEIPTS, FONTS, SECURITY,
     *                                 OPERATING_SYSTEM, LICENSED_SOFTWARE, IBEACONS, SOFTWARE_UPDATES,
     *                                 EXTENSION_ATTRIBUTES, CONTENT_CACHING, GROUP_MEMBERSHIPS
     * @return Page<ComputerSummary>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
        array $section = [],
    ): Page {
        $response = $this->http->get('/v2/computers-inventory', $this->buildQuery([
            'page' => $page,
            'page-size' => $pageSize,
            'sort' => $sort ?: null,
            'filter' => $filter,
            'section' => $section ?: null,
        ]));

        return new Page(
            results: array_map(ComputerSummary::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize: $pageSize,
        );
    }

    /**
     * Retrieve a specific computer's inventory record.
     *
     * @param  list<string>  $section  Optional inventory sections to include.
     */
    public function find(string $id, array $section = []): ComputerDetail
    {
        $response = $this->http->get(
            "/v2/computers-inventory/{$id}",
            $this->buildQuery(['section' => $section ?: null])
        );

        return ComputerDetail::fromArray($response->json());
    }

    /**
     * Retrieve full detail for a computer, including all available inventory sections.
     */
    public function detail(string $id): ComputerDetail
    {
        return ComputerDetail::fromArray(
            $this->http->get("/v2/computers-inventory-detail/{$id}")->json()
        );
    }

    /**
     * Partially update a computer's inventory record.
     *
     * Only the provided fields are updated; omitted fields remain unchanged.
     *
     * @param  array<string,mixed>  $data  Fields to update.
     */
    public function patch(string $id, array $data): ComputerDetail
    {
        return ComputerDetail::fromArray(
            $this->http->patch("/v2/computers-inventory/{$id}", $data)->json()
        );
    }

    /**
     * Delete a computer from Jamf Pro.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v2/computers-inventory/{$id}");
    }

    /**
     * Delete multiple computers by ID in a single request.
     *
     * @param  list<string>  $ids  Computer IDs to delete.
     */
    public function deleteMultiple(array $ids): void
    {
        $this->http->post('/v2/computers-inventory/delete-multiple', ['ids' => $ids]);
    }

    /**
     * Retrieve the available filter field names and values for the computer inventory search.
     *
     * @return array<string,mixed>
     */
    public function filterChoices(): array
    {
        return $this->http->get('/v2/computers-inventory/filter-choices')->json();
    }
}
