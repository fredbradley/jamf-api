<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources\Concerns;

/**
 * Provides CSV export functionality for resources that support it.
 *
 * The using class must implement {@see exportPath()} returning the API path
 * for the export endpoint.
 */
trait HasCsvExport
{
    /**
     * Export history or records as a CSV string.
     *
     * @param  list<string>  $sort  Sort fields.
     * @param  string|null  $filter  RSQL filter string.
     * @param  list<string>  $fields  Specific fields to include in the export.
     */
    public function exportCsv(
        array $sort = [],
        ?string $filter = null,
        array $fields = [],
    ): string {
        $response = $this->http->post($this->exportPath(), $this->buildQuery([
            'sort' => $sort ?: null,
            'filter' => $filter,
            'fields' => $fields ?: null,
        ]));

        return $response->body();
    }

    /**
     * The API path for the export endpoint of this resource.
     *
     * e.g. '/v1/departments/history/export'
     */
    abstract protected function exportPath(): string;
}
