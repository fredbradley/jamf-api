<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Pagination\Page;

/**
 * Mobile device MDM commands — send and query management commands to mobile devices.
 *
 * Required privilege: Send Mobile Device Management Commands
 */
class MobileDeviceManagementCommandsResource extends AbstractResource
{
    /**
     * List MDM commands sent to mobile devices.
     *
     * @param  int           $page
     * @param  int           $pageSize
     * @param  list<string>  $sort
     * @param  string|null   $filter  RSQL filter, e.g. 'status=="Pending"'.
     * @return Page<array<string,mixed>>
     */
    public function list(
        int $page = 0,
        int $pageSize = 100,
        array $sort = [],
        ?string $filter = null,
    ): Page {
        $response = $this->http->get('/v2/mobile-device-management-commands', $this->buildQuery([
            'page'      => $page,
            'page-size' => $pageSize,
            'sort'      => $sort ?: null,
            'filter'    => $filter,
        ]));

        return new Page(
            results:    $response->json('results', []),
            totalCount: $response->json('totalCount', 0),
            pageNumber: $page,
            pageSize:   $pageSize,
        );
    }

    /**
     * Send an MDM command to one or more mobile devices.
     *
     * @param  list<string>        $clientManagementIds  Device management IDs.
     * @param  string              $commandType          Command name, e.g. 'DEVICE_LOCK', 'ERASE_DEVICE'.
     * @param  array<string,mixed> $params               Additional command parameters.
     * @return array<string,mixed>
     */
    public function send(
        array $clientManagementIds,
        string $commandType,
        array $params = [],
    ): array {
        return $this->http->post('/v2/mobile-device-management-commands', [
            'clientManagementIds' => $clientManagementIds,
            'commandType'         => $commandType,
            ...$params,
        ])->json();
    }

    /**
     * Enable Bluetooth on one or more supervised mobile devices.
     *
     * Requires device supervision. Sends the SETTINGS command with bluetooth=true.
     *
     * @param  list<string>  $clientManagementIds
     * @return array<string,mixed>
     */
    public function enableBluetooth(array $clientManagementIds): array
    {
        return $this->send($clientManagementIds, 'SETTINGS', ['bluetooth' => true]);
    }

    /**
     * Disable Bluetooth on one or more supervised mobile devices.
     *
     * Requires device supervision. Sends the SETTINGS command with bluetooth=false.
     *
     * @param  list<string>  $clientManagementIds
     * @return array<string,mixed>
     */
    public function disableBluetooth(array $clientManagementIds): array
    {
        return $this->send($clientManagementIds, 'SETTINGS', ['bluetooth' => false]);
    }

    /**
     * Enable data roaming on one or more supervised mobile devices.
     *
     * @param  list<string>  $clientManagementIds
     * @return array<string,mixed>
     */
    public function enableDataRoaming(array $clientManagementIds): array
    {
        return $this->send($clientManagementIds, 'SETTINGS', ['dataRoaming' => true]);
    }

    /**
     * Disable data roaming on one or more supervised mobile devices.
     *
     * @param  list<string>  $clientManagementIds
     * @return array<string,mixed>
     */
    public function disableDataRoaming(array $clientManagementIds): array
    {
        return $this->send($clientManagementIds, 'SETTINGS', ['dataRoaming' => false]);
    }

    /**
     * Enable voice roaming on one or more supervised mobile devices.
     *
     * @param  list<string>  $clientManagementIds
     * @return array<string,mixed>
     */
    public function enableVoiceRoaming(array $clientManagementIds): array
    {
        return $this->send($clientManagementIds, 'SETTINGS', ['voiceRoaming' => true]);
    }

    /**
     * Disable voice roaming on one or more supervised mobile devices.
     *
     * @param  list<string>  $clientManagementIds
     * @return array<string,mixed>
     */
    public function disableVoiceRoaming(array $clientManagementIds): array
    {
        return $this->send($clientManagementIds, 'SETTINGS', ['voiceRoaming' => false]);
    }

    /**
     * Enable Personal Hotspot on one or more supervised mobile devices.
     *
     * @param  list<string>  $clientManagementIds
     * @return array<string,mixed>
     */
    public function enablePersonalHotspot(array $clientManagementIds): array
    {
        return $this->send($clientManagementIds, 'SETTINGS', ['personalHotspot' => true]);
    }

    /**
     * Disable Personal Hotspot on one or more supervised mobile devices.
     *
     * @param  list<string>  $clientManagementIds
     * @return array<string,mixed>
     */
    public function disablePersonalHotspot(array $clientManagementIds): array
    {
        return $this->send($clientManagementIds, 'SETTINGS', ['personalHotspot' => false]);
    }
}
