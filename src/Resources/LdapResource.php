<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Resources;

/**
 * LDAP servers and group search.
 *
 * Required privilege: Read LDAP Servers
 */
class LdapResource extends AbstractResource
{
    /**
     * List all configured LDAP and Cloud Identity Provider servers.
     *
     * @return list<array<string,mixed>>
     */
    public function servers(): array
    {
        return $this->http->get('/v1/ldap/servers')->json('servers', []);
    }

    /**
     * Search for LDAP access groups matching the given text.
     *
     * @param  string  $q  Search text (partial group name).
     * @param  string|null  $serverId  Limit search to a specific LDAP server ID.
     * @param  bool  $includeUsers  Whether to include user objects in results.
     * @return list<array<string,mixed>>
     */
    public function searchGroups(
        string $q,
        ?string $serverId = null,
        bool $includeUsers = false,
    ): array {
        return $this->http->get('/v1/ldap/groups', $this->buildQuery([
            'q' => $q,
            'ldapServerId' => $serverId,
            'includeUsers' => $includeUsers ? 'true' : null,
        ]))->json('groups', []);
    }
}
