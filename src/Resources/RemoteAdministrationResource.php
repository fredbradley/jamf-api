<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Pagination\Page;

/**
 * Remote administration — Team Viewer integration for remote support sessions.
 *
 * Required privilege: Read Remote Administration
 */
class RemoteAdministrationResource extends AbstractResource
{
    /**
     * List all remote administration configurations.
     *
     * @return Page<array<string,mixed>>
     */
    public function list(int $page = 0, int $pageSize = 100): Page
    {
        $response = $this->http->get('/preview/remote-administration-configurations', $this->buildQuery([
            'page'      => $page,
            'page-size' => $pageSize,
        ]));

        return new Page(
            results:    $response->json('results', []),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize:   $pageSize,
        );
    }

    /**
     * Create a new Team Viewer remote administration configuration.
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function createTeamViewer(array $data): array
    {
        return $this->http->post('/preview/remote-administration-configurations/team-viewer', $data)->json();
    }

    /**
     * Retrieve a Team Viewer configuration by ID.
     *
     * @return array<string,mixed>
     */
    public function findTeamViewer(string $id): array
    {
        return $this->http->get("/preview/remote-administration-configurations/team-viewer/{$id}")->json();
    }

    /**
     * Update a Team Viewer configuration.
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function updateTeamViewer(string $id, array $data): array
    {
        return $this->http->patch("/preview/remote-administration-configurations/team-viewer/{$id}", $data)->json();
    }

    /**
     * Delete a Team Viewer configuration.
     */
    public function deleteTeamViewer(string $id): void
    {
        $this->http->delete("/preview/remote-administration-configurations/team-viewer/{$id}");
    }

    /**
     * Check the connection status of a Team Viewer configuration.
     *
     * @return array<string,mixed>
     */
    public function teamViewerStatus(string $id): array
    {
        return $this->http->get("/preview/remote-administration-configurations/team-viewer/{$id}/status")->json();
    }

    /**
     * List remote sessions for a Team Viewer configuration.
     *
     * @return Page<array<string,mixed>>
     */
    public function sessions(string $configurationId, int $page = 0, int $pageSize = 100): Page
    {
        $response = $this->http->get(
            "/preview/remote-administration-configurations/team-viewer/{$configurationId}/sessions",
            $this->buildQuery(['page' => $page, 'page-size' => $pageSize])
        );

        return new Page(
            results:    $response->json('results', []),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize:   $pageSize,
        );
    }

    /**
     * Create a new remote support session.
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function createSession(string $configurationId, array $data): array
    {
        return $this->http->post(
            "/preview/remote-administration-configurations/team-viewer/{$configurationId}/sessions",
            $data
        )->json();
    }

    /**
     * Close a remote support session.
     */
    public function closeSession(string $configurationId, string $sessionId): void
    {
        $this->http->post(
            "/preview/remote-administration-configurations/team-viewer/{$configurationId}/sessions/{$sessionId}/close"
        );
    }

    /**
     * Resend the session notification to the end user.
     */
    public function resendSessionNotification(string $configurationId, string $sessionId): void
    {
        $this->http->post(
            "/preview/remote-administration-configurations/team-viewer/{$configurationId}/sessions/{$sessionId}/resend-notification"
        );
    }
}
