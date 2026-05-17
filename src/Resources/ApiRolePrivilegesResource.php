<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

/**
 * Jamf Pro API role privileges.
 *
 * Lists the available privilege strings that can be assigned to API roles.
 */
class ApiRolePrivilegesResource extends AbstractResource
{
    /**
     * List all available API role privilege strings.
     *
     * @return list<string>
     */
    public function list(): array
    {
        return $this->http->get('/v1/api-role-privileges')->json('privileges', []);
    }

    /**
     * Search for privilege strings by partial name.
     *
     * @param  string  $name  Partial privilege name to search for.
     * @return list<string>
     */
    public function search(string $name): array
    {
        return $this->http->get('/v1/api-role-privileges/search', ['name' => $name])
            ->json('privileges', []);
    }
}
