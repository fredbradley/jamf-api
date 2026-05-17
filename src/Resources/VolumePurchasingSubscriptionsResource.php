<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Pagination\Page;

/**
 * Volume Purchasing subscriptions — notification subscriptions for VPP events.
 *
 * Required privilege: Read Volume Purchasing Subscriptions / Update Volume Purchasing Subscriptions /
 *                     Create Volume Purchasing Subscriptions / Delete Volume Purchasing Subscriptions
 */
class VolumePurchasingSubscriptionsResource extends AbstractResource
{
    /**
     * List all Volume Purchasing subscriptions.
     *
     * @return Page<array<string,mixed>>
     */
    public function list(int $page = 0, int $pageSize = 100): Page
    {
        $response = $this->http->get('/v1/volume-purchasing-subscriptions', $this->buildQuery([
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
     * Retrieve a Volume Purchasing subscription by ID.
     *
     * @return array<string,mixed>
     */
    public function find(string $id): array
    {
        return $this->http->get("/v1/volume-purchasing-subscriptions/{$id}")->json();
    }

    /**
     * Create a new Volume Purchasing subscription.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function create(array $data): array
    {
        return $this->http->post('/v1/volume-purchasing-subscriptions', $data)->json();
    }

    /**
     * Update a Volume Purchasing subscription (full replacement).
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function update(string $id, array $data): array
    {
        return $this->http->put("/v1/volume-purchasing-subscriptions/{$id}", $data)->json();
    }

    /**
     * Delete a Volume Purchasing subscription.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v1/volume-purchasing-subscriptions/{$id}");
    }
}
