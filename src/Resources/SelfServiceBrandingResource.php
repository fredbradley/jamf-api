<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Resources;

use FredBradley\JamfApi\Pagination\Page;

/**
 * Self Service branding — customize the look of Self Service for iOS and macOS.
 *
 * Required privilege: Read Self Service Branding / Update Self Service Branding /
 *                     Create Self Service Branding / Delete Self Service Branding
 */
class SelfServiceBrandingResource extends AbstractResource
{
    /**
     * List iOS branding configurations.
     *
     * @return Page<array<string,mixed>>
     */
    public function listIos(int $page = 0, int $pageSize = 100): Page
    {
        $response = $this->http->get('/v1/self-service/branding/ios', $this->buildQuery([
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
     * Retrieve an iOS branding configuration by ID.
     *
     * @return array<string,mixed>
     */
    public function findIos(string $id): array
    {
        return $this->http->get("/v1/self-service/branding/ios/{$id}")->json();
    }

    /**
     * Create a new iOS branding configuration.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function createIos(array $data): array
    {
        return $this->http->post('/v1/self-service/branding/ios', $data)->json();
    }

    /**
     * Update an iOS branding configuration.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function updateIos(string $id, array $data): array
    {
        return $this->http->put("/v1/self-service/branding/ios/{$id}", $data)->json();
    }

    /**
     * Delete an iOS branding configuration.
     */
    public function deleteIos(string $id): void
    {
        $this->http->delete("/v1/self-service/branding/ios/{$id}");
    }

    /**
     * List macOS branding configurations.
     *
     * @return Page<array<string,mixed>>
     */
    public function listMacos(int $page = 0, int $pageSize = 100): Page
    {
        $response = $this->http->get('/v1/self-service/branding/macos', $this->buildQuery([
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
     * Retrieve a macOS branding configuration by ID.
     *
     * @return array<string,mixed>
     */
    public function findMacos(string $id): array
    {
        return $this->http->get("/v1/self-service/branding/macos/{$id}")->json();
    }

    /**
     * Create a new macOS branding configuration.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function createMacos(array $data): array
    {
        return $this->http->post('/v1/self-service/branding/macos', $data)->json();
    }

    /**
     * Update a macOS branding configuration.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function updateMacos(string $id, array $data): array
    {
        return $this->http->put("/v1/self-service/branding/macos/{$id}", $data)->json();
    }

    /**
     * Delete a macOS branding configuration.
     */
    public function deleteMacos(string $id): void
    {
        $this->http->delete("/v1/self-service/branding/macos/{$id}");
    }

    /**
     * Upload a branding image (icon).
     *
     * @param  string  $filePath  Local path to the image file (PNG recommended).
     * @return array<string,mixed> Contains the uploaded image ID.
     */
    public function uploadImage(string $filePath): array
    {
        return $this->http->postMultipart('/v2/self-service/branding/images', [
            ['name' => 'file', 'contents' => fopen($filePath, 'r'), 'filename' => basename($filePath)],
        ])->json();
    }
}
