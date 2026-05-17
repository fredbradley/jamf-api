<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Pagination\Page;

/**
 * Supervision identities — Apple Configurator 2 supervision certificates.
 *
 * Required privilege: Read Supervision Identities / Update Supervision Identities /
 *                     Create Supervision Identities / Delete Supervision Identities
 */
class SupervisionIdentitiesResource extends AbstractResource
{
    /**
     * List all supervision identities.
     *
     * @param  int           $page
     * @param  int           $pageSize
     * @param  list<string>  $sort
     * @return Page<array<string,mixed>>
     */
    public function list(int $page = 0, int $pageSize = 100, array $sort = []): Page
    {
        $response = $this->http->get('/v2/supervision-identities', $this->buildQuery([
            'page'      => $page,
            'page-size' => $pageSize,
            'sort'      => $sort ?: null,
        ]));

        return new Page(
            results:    $response->json('results', []),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize:   $pageSize,
        );
    }

    /**
     * Retrieve a specific supervision identity by ID.
     *
     * @return array<string,mixed>
     */
    public function find(string $id): array
    {
        return $this->http->get("/v2/supervision-identities/{$id}")->json();
    }

    /**
     * Create a new supervision identity.
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function create(array $data): array
    {
        return $this->http->post('/v2/supervision-identities', $data)->json();
    }

    /**
     * Update a supervision identity (full replacement).
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function update(string $id, array $data): array
    {
        return $this->http->put("/v2/supervision-identities/{$id}", $data)->json();
    }

    /**
     * Delete a supervision identity by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v2/supervision-identities/{$id}");
    }

    /**
     * Download the supervision identity certificate as a .p12 file.
     *
     * Returns the raw binary content of the PKCS#12 file.
     *
     * @param  string  $id        Supervision identity ID.
     * @param  string  $password  The passphrase for the .p12 file.
     */
    public function download(string $id, string $password): string
    {
        return $this->http->post("/v2/supervision-identities/{$id}/download", [
            'password' => $password,
        ])->body();
    }
}
