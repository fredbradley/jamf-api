<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Resources;

use FredBradley\JamfApi\Data\Common\HistoryNote;
use FredBradley\JamfApi\Data\Prestage\MobileDevicePrestage;
use FredBradley\JamfApi\Pagination\Page;
use FredBradley\JamfApi\Resources\Concerns\HasHistory;

/**
 * Mobile device prestages — automated device enrollment profiles for iOS/iPadOS/tvOS.
 *
 * Required privilege: Read Mobile Device PreStages / Update Mobile Device PreStages /
 *                     Create Mobile Device PreStages / Delete Mobile Device PreStages
 */
class MobileDevicePrestagesResource extends AbstractResource
{
    use HasHistory;

    private string $currentId = '';

    /**
     * List all mobile device prestages.
     *
     * @param  list<string>  $sort
     * @return Page<MobileDevicePrestage>
     */
    public function list(int $page = 0, int $pageSize = 100, array $sort = []): Page
    {
        $response = $this->http->get('/v2/mobile-device-prestages', $this->buildQuery([
            'page' => $page,
            'page-size' => $pageSize,
            'sort' => $sort ?: null,
        ]));

        return new Page(
            results: array_map(MobileDevicePrestage::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize: $pageSize,
        );
    }

    /**
     * Retrieve a specific mobile device prestage by ID.
     */
    public function find(string $id): MobileDevicePrestage
    {
        return MobileDevicePrestage::fromArray(
            $this->http->get("/v2/mobile-device-prestages/{$id}")->json()
        );
    }

    /**
     * Create a new mobile device prestage.
     *
     * @param  array<string,mixed>  $data
     */
    public function create(array $data): MobileDevicePrestage
    {
        return MobileDevicePrestage::fromArray(
            $this->http->post('/v2/mobile-device-prestages', $data)->json()
        );
    }

    /**
     * Update a mobile device prestage (full replacement).
     *
     * @param  array<string,mixed>  $data
     */
    public function update(string $id, array $data): MobileDevicePrestage
    {
        return MobileDevicePrestage::fromArray(
            $this->http->put("/v2/mobile-device-prestages/{$id}", $data)->json()
        );
    }

    /**
     * Delete a mobile device prestage by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v2/mobile-device-prestages/{$id}");
    }

    /**
     * Retrieve the scope (assigned serial numbers) for a mobile device prestage.
     *
     * @return array<string,mixed>
     */
    public function scope(string $id): array
    {
        return $this->http->get("/v2/mobile-device-prestages/{$id}/scope")->json();
    }

    /**
     * Replace the entire scope of a mobile device prestage.
     *
     * @param  list<string>  $serialNumbers
     */
    public function replaceScope(string $id, array $serialNumbers, int $versionLock): array
    {
        return $this->http->put("/v2/mobile-device-prestages/{$id}/scope", [
            'serialNumbers' => $serialNumbers,
            'versionLock' => $versionLock,
        ])->json();
    }

    /**
     * Add serial numbers to the scope of a mobile device prestage.
     *
     * @param  list<string>  $serialNumbers
     */
    public function addToScope(string $id, array $serialNumbers, int $versionLock): array
    {
        return $this->http->post("/v2/mobile-device-prestages/{$id}/scope", [
            'serialNumbers' => $serialNumbers,
            'versionLock' => $versionLock,
        ])->json();
    }

    /**
     * Remove serial numbers from the scope of a mobile device prestage.
     *
     * @param  list<string>  $serialNumbers
     */
    public function removeFromScope(string $id, array $serialNumbers, int $versionLock): array
    {
        return $this->http->post("/v2/mobile-device-prestages/{$id}/scope/delete-multiple", [
            'serialNumbers' => $serialNumbers,
            'versionLock' => $versionLock,
        ])->json();
    }

    /**
     * List syncs for a mobile device prestage.
     *
     * @return array<string,mixed>
     */
    public function syncs(string $id): array
    {
        return $this->http->get("/v2/mobile-device-prestages/{$id}/syncs")->json();
    }

    /**
     * Retrieve the latest sync status for a mobile device prestage.
     *
     * @return array<string,mixed>
     */
    public function latestSync(string $id): array
    {
        return $this->http->get("/v2/mobile-device-prestages/{$id}/syncs/latest")->json();
    }

    /**
     * Retrieve history for a specific mobile device prestage.
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
        return "/v2/mobile-device-prestages/{$this->currentId}/history";
    }
}
