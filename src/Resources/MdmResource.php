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
     * IMPORTANT: The Jamf API requires at least one filter field — calling this
     * without a filter will return a 400 error. Filterable fields: uuid,
     * clientManagementId, command, status, clientType, dateSent, validAfter,
     * dateCompleted, profileId, profileIdentifier, active.
     *
     * Example: list(filter: 'clientManagementId==aaaaaaaa-3f1e-4b3a-a5b3-ca0cd7430937')
     *
     * @param  list<string>  $sort
     * @return Page<array<string,mixed>>
     *
     * @throws \InvalidArgumentException if no filter is provided.
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        if (empty($filter)) {
            throw new \InvalidArgumentException(
                'MdmResource::list() requires at least one filter field. '.
                'Example: list(filter: \'clientManagementId==your-uuid\')'
            );
        }

        $response = $this->http->get('/v2/mdm/commands', $this->buildQuery([
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
     * Send a blank push notification to one or more devices.
     *
     * @param  list<string>  $clientManagementIds  Device management IDs (UUIDs).
     * @return array<string,mixed>
     */
    public function blankPush(array $clientManagementIds): array
    {
        return $this->http->post('/v2/mdm/blank-push', [
            'clientManagementIds' => $clientManagementIds,
        ])->json();
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
