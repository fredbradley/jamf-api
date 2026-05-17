<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Resources\Concerns\HasHistory;

/**
 * Teacher app settings — configure the Jamf Teacher companion app for educators.
 *
 * Required privilege: Read Teacher App / Update Teacher App
 */
class TeacherAppResource extends AbstractResource
{
    use HasHistory;

    /**
     * Retrieve the current Teacher app settings.
     *
     * @return array<string,mixed>
     */
    public function get(): array
    {
        return $this->http->get('/v2/teacher-app')->json();
    }

    /**
     * Update Teacher app settings.
     *
     * @param  array<string,mixed> $data
     * @return array<string,mixed>
     */
    public function save(array $data): array
    {
        return $this->http->put('/v2/teacher-app', $data)->json();
    }

    protected function historyPath(): string
    {
        return '/v1/teacher-app/history';
    }
}
