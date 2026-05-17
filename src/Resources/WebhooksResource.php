<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Data\Common\HistoryNote;
use Cranleigh\JamfApi\Data\Webhook\Webhook;
use Cranleigh\JamfApi\Pagination\Page;
use Cranleigh\JamfApi\Resources\Concerns\HasHistory;

/**
 * Jamf Pro webhooks — configure HTTP callbacks for Jamf Pro events.
 *
 * Required privilege: Read Webhooks / Update Webhooks / Create Webhooks / Delete Webhooks
 */
class WebhooksResource extends AbstractResource
{
    use HasHistory;

    private string $currentId = '';

    /**
     * List all webhooks.
     *
     * @param  int           $page
     * @param  int           $pageSize
     * @param  list<string>  $sort
     * @param  string|null   $filter
     * @return Page<Webhook>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v1/webhooks', $this->buildQuery([
            'page'      => $page,
            'page-size' => $pageSize,
            'sort'      => $sort ?: null,
            'filter'    => $filter,
        ]));

        return new Page(
            results:    array_map(Webhook::fromArray(...), $response->json('results', [])),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize:   $pageSize,
        );
    }

    /**
     * Retrieve a specific webhook by ID.
     */
    public function find(string $id): Webhook
    {
        return Webhook::fromArray($this->http->get("/v1/webhooks/{$id}")->json());
    }

    /**
     * Create a new webhook.
     *
     * @param  array<string,mixed> $data  Webhook properties (name, url, event, contentType, enabled, etc.).
     */
    public function create(array $data): Webhook
    {
        return Webhook::fromArray($this->http->post('/v1/webhooks', $data)->json());
    }

    /**
     * Update an existing webhook (full replacement).
     *
     * @param  array<string,mixed> $data
     */
    public function update(string $id, array $data): Webhook
    {
        return Webhook::fromArray($this->http->put("/v1/webhooks/{$id}", $data)->json());
    }

    /**
     * Delete a webhook by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v1/webhooks/{$id}");
    }

    /**
     * Retrieve history for a specific webhook.
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
        return "/v1/webhooks/{$this->currentId}/history";
    }
}
