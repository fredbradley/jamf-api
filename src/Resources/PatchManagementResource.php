<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Resources;

/**
 * Patch management global settings.
 *
 * Controls the global on/off state and disclaimer acceptance for patch management.
 *
 * Required privilege: Read Patch Management / Update Patch Management
 */
class PatchManagementResource extends AbstractResource
{
    /**
     * Retrieve current patch management settings.
     *
     * @return array<string,mixed>
     */
    public function get(): array
    {
        return $this->http->get('/v2/patch-management')->json();
    }

    /**
     * Update patch management settings.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function save(array $data): array
    {
        return $this->http->put('/v2/patch-management', $data)->json();
    }

    /**
     * Accept the patch management disclaimer.
     *
     * This must be called once before patch management can be enabled.
     */
    public function acceptDisclaimer(): void
    {
        $this->http->post('/v2/patch-management-accept-disclaimer');
    }
}
