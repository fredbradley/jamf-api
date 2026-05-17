<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Resources\Concerns\HasHistory;

/**
 * Jamf Pro enrollment settings.
 *
 * Controls global enrollment configuration including MDM profile customisation,
 * user-initiated enrollment, and account-driven enrollment.
 *
 * Required privilege: Read Enrollment / Update Enrollment
 */
class EnrollmentResource extends AbstractResource
{
    use HasHistory;

    /**
     * Retrieve the current enrollment settings.
     *
     * @return array<string,mixed>
     */
    public function get(): array
    {
        return $this->http->get('/v2/enrollment')->json();
    }

    /**
     * Update enrollment settings.
     *
     * @param  array<string,mixed>  $data  Enrollment settings payload.
     * @return array<string,mixed>
     */
    public function save(array $data): array
    {
        return $this->http->put('/v2/enrollment', $data)->json();
    }

    /**
     * Retrieve the list of available enrollment languages.
     *
     * @return array<string,mixed>
     */
    public function languages(): array
    {
        return $this->http->get('/v2/enrollment/languages')->json();
    }

    /**
     * Retrieve the enrollment messaging for a specific language.
     *
     * @param  string  $languageId  e.g. 'en'.
     * @return array<string,mixed>
     */
    public function languageMessaging(string $languageId): array
    {
        return $this->http->get("/v2/enrollment/languages/{$languageId}")->json();
    }

    /**
     * Update enrollment messaging for a specific language.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function updateLanguageMessaging(string $languageId, array $data): array
    {
        return $this->http->put("/v2/enrollment/languages/{$languageId}", $data)->json();
    }

    /**
     * Delete enrollment messaging for a language (resets to default).
     */
    public function deleteLanguageMessaging(string $languageId): void
    {
        $this->http->delete("/v2/enrollment/languages/{$languageId}");
    }

    protected function historyPath(): string
    {
        return '/v1/enrollment/history';
    }
}
