<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Resources;

/**
 * Computer management information — provides a summary of all management
 * data associated with a specific computer (policies, profiles, etc.).
 *
 * Required privilege: Read Computers
 */
class ComputerManagementResource extends AbstractResource
{
    /**
     * Retrieve management data for a specific computer.
     *
     * Returns applied policies, profiles, extension attributes, and more.
     *
     * @param  string  $id  The computer ID.
     * @param  list<string>  $section  Optional sections to retrieve. Available: GENERAL,
     *                                 MANAGEMENT_INFO, REMOTE_ADMINISTRATION, PATCH,
     *                                 POLICY, EBOOKS, RESTRICTED_SOFTWARE.
     * @return array<string,mixed>
     */
    public function get(string $id, array $section = []): array
    {
        return $this->http->get(
            "/v2/computer-management/{$id}",
            $this->buildQuery(['section' => $section ?: null])
        )->json();
    }
}
