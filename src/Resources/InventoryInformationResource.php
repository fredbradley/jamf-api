<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

/**
 * Inventory information — summary counts of managed devices and users.
 *
 * Required privilege: Read Computers / Read Mobile Devices
 */
class InventoryInformationResource extends AbstractResource
{
    /**
     * Retrieve inventory summary information.
     *
     * Returns counts for managed/unmanaged computers, mobile devices, and users.
     *
     * @return array<string,mixed>
     */
    public function get(): array
    {
        return $this->http->get('/v1/inventory-information')->json();
    }
}
