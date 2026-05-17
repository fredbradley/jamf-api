<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Resources;

use FredBradley\JamfApi\Data\Common\HistoryNote;
use FredBradley\JamfApi\Data\Enrollment\DeviceEnrollment;
use FredBradley\JamfApi\Pagination\Page;
use FredBradley\JamfApi\Resources\Concerns\HasHistory;

/**
 * Apple Device Enrollment Program (DEP/ADE) instances.
 *
 * Manages the connection between Jamf Pro and Apple Business Manager /
 * Apple School Manager for automated device enrollment.
 *
 * Required privilege: Read Device Enrollments / Update Device Enrollments /
 *                     Create Device Enrollments / Delete Device Enrollments
 */
class DeviceEnrollmentsResource extends AbstractResource
{
    use HasHistory;

    private string $currentId = '';

    /**
     * List all device enrollment (DEP) instances.
     *
     * @param  list<string>  $sort
     * @return Page<DeviceEnrollment>
     */
    public function list(int $page = 0, int $pageSize = 100, array $sort = []): Page
    {
        $response = $this->http->get('/v1/device-enrollments', $this->buildQuery([
            'page' => $page,
            'page-size' => $pageSize,
            'sort' => $sort ?: null,
        ]));

        return new Page(
            results: array_map(DeviceEnrollment::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize: $pageSize,
        );
    }

    /**
     * Retrieve a specific device enrollment instance by ID.
     */
    public function find(string $id): DeviceEnrollment
    {
        return DeviceEnrollment::fromArray($this->http->get("/v1/device-enrollments/{$id}")->json());
    }

    /**
     * Create a new device enrollment instance by uploading a DEP token.
     *
     * @param  string  $tokenFilePath  Path to the .p7m token file from Apple Business Manager.
     * @param  string  $displayName  Human-readable name for this instance.
     * @return array<string,mixed>
     */
    public function uploadToken(string $tokenFilePath, string $displayName): array
    {
        return $this->http->postMultipart('/v1/device-enrollments/upload-token', [
            ['name' => 'file', 'contents' => fopen($tokenFilePath, 'r'), 'filename' => basename($tokenFilePath)],
        ])->json();
    }

    /**
     * Update an existing device enrollment instance.
     *
     * @param  array<string,mixed>  $data
     */
    public function update(string $id, array $data): DeviceEnrollment
    {
        return DeviceEnrollment::fromArray(
            $this->http->put("/v1/device-enrollments/{$id}", $data)->json()
        );
    }

    /**
     * Delete a device enrollment instance by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v1/device-enrollments/{$id}");
    }

    /**
     * List devices associated with a device enrollment instance.
     *
     * @return Page<array<string,mixed>>
     */
    public function devices(string $id, int $page = 0, int $pageSize = 100): Page
    {
        $response = $this->http->get("/v2/device-enrollments/{$id}/devices", $this->buildQuery([
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

    /**
     * List sync operations for a device enrollment instance.
     *
     * @return list<array<string,mixed>>
     */
    public function syncs(string $id): array
    {
        return $this->http->get("/v1/device-enrollments/{$id}/syncs")->json('results', []);
    }

    /**
     * Trigger a manual sync for a device enrollment instance.
     *
     * @return array<string,mixed>
     */
    public function sync(string $id): array
    {
        return $this->http->post("/v1/device-enrollments/{$id}/syncs")->json();
    }

    /**
     * Get the latest sync result for a device enrollment instance.
     *
     * @return array<string,mixed>
     */
    public function latestSync(string $id): array
    {
        return $this->http->get("/v1/device-enrollments/{$id}/syncs/latest")->json();
    }

    /**
     * Retrieve history for a specific device enrollment instance.
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
        return "/v1/device-enrollments/{$this->currentId}/history";
    }
}
