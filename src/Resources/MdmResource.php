<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Resources;

use FredBradley\JamfApi\Pagination\Page;

/**
 * MDM commands — send Apple MDM protocol commands to managed devices.
 *
 * Required privilege: Send Computer Remote Commands / Send Mobile Device Management Commands
 */
class MdmResource extends AbstractResource
{
    /**
     * List MDM commands sent to devices.
     *
     * @param  list<string>  $sort
     * @return Page<array<string,mixed>>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v1/mdm/commands', $this->buildQuery([
            'page' => $page,
            'page-size' => $pageSize,
            'sort' => $sort ?: null,
            'filter' => $filter,
        ]));

        return new Page(
            results: $response->json('results', []),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize: $pageSize,
        );
    }

    /**
     * Send an MDM command to a device.
     *
     * @param  string  $clientManagementId  Device management ID (UUID).
     * @param  string  $commandType  Command name from MdmCommandType enum.
     * @param  array<string,mixed>  $params  Additional command parameters (e.g. lostModeMessage for ENABLE_LOST_MODE).
     * @return array<string,mixed>
     */
    public function send(
        string $clientManagementId,
        string $commandType,
        array $params = [],
    ): array {
        return $this->http->post('/v2/mdm/commands', [
            'clientData' => [
                ['managementId' => $clientManagementId],
            ],
            'commandData' => [
                'commandType' => $commandType,
                ...$params,
            ],
        ])->json();
    }

    /**
     * Redeploy the Jamf Management Framework to a specific computer.
     *
     * @param  string  $id  Computer management ID.
     */
    public function redeployManagementFramework(string $id): void
    {
        $this->http->post("/v2/jamf-management-framework/redeploy/{$id}");
    }
}
