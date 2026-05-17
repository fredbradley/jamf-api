<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Data\Common\HistoryNote;
use Cranleigh\JamfApi\Pagination\Page;
use Cranleigh\JamfApi\Resources\Concerns\HasCsvExport;
use Cranleigh\JamfApi\Resources\Concerns\HasHistory;

/**
 * Jamf Pro activation code and organisation name management.
 *
 * Required privilege: Update Activation Code
 */
class ActivationCodeResource extends AbstractResource
{
    use HasHistory;
    use HasCsvExport;

    /**
     * Update the Jamf Pro activation code.
     *
     * @param  string  $organizationName  Your organisation name.
     * @param  string  $code             The activation code.
     * @return array<string,mixed>
     */
    public function update(string $organizationName, string $code): array
    {
        return $this->http->put('/v1/activation-code', [
            'organizationName' => $organizationName,
            'code'             => $code,
        ])->json();
    }

    /**
     * Update the organisation name only (PATCH).
     *
     * @param  string  $organizationName
     * @return array<string,mixed>
     */
    public function updateOrganizationName(string $organizationName): array
    {
        return $this->http->patch('/v1/activation-code/organization-name', [
            'organizationName' => $organizationName,
        ])->json();
    }

    protected function historyPath(): string
    {
        return '/v1/activation-code/history';
    }

    protected function exportPath(): string
    {
        return '/v1/activation-code/history/export';
    }
}
