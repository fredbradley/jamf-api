<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

/**
 * Cloud LDAP (Cloud Identity Provider) configuration.
 *
 * Required privilege: Read LDAP Servers / Update LDAP Servers / Create LDAP Servers / Delete LDAP Servers
 */
class CloudLdapResource extends AbstractResource
{
    /**
     * Retrieve the default Cloud LDAP settings for a given provider.
     *
     * @param  string  $provider  Provider type, e.g. 'GOOGLE', 'AZURE'.
     * @return array<string,mixed>
     */
    public function defaults(string $provider): array
    {
        return $this->http->get("/v1/cloud-ldap/defaults/{$provider}")->json();
    }

    /**
     * Create a new Cloud LDAP server configuration.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function create(array $data): array
    {
        return $this->http->post('/v1/cloud-ldap', $data)->json();
    }

    /**
     * Retrieve a Cloud LDAP server configuration by ID.
     *
     * @return array<string,mixed>
     */
    public function find(string $id): array
    {
        return $this->http->get("/v1/cloud-ldap/{$id}")->json();
    }

    /**
     * Update a Cloud LDAP server configuration (full replacement).
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function update(string $id, array $data): array
    {
        return $this->http->put("/v1/cloud-ldap/{$id}", $data)->json();
    }

    /**
     * Partially update a Cloud LDAP server configuration.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function patch(string $id, array $data): array
    {
        return $this->http->patch("/v1/cloud-ldap/{$id}", $data)->json();
    }

    /**
     * Delete a Cloud LDAP server configuration by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("/v1/cloud-ldap/{$id}");
    }

    /**
     * Check the connection status of a Cloud LDAP server.
     *
     * @return array<string,mixed>
     */
    public function connectionStatus(string $id): array
    {
        return $this->http->get("/v1/cloud-ldap/{$id}/connection/status")->json();
    }

    /**
     * Retrieve the field mappings for a Cloud LDAP server.
     *
     * @return array<string,mixed>
     */
    public function mappings(string $id): array
    {
        return $this->http->get("/v1/cloud-ldap/{$id}/mappings")->json();
    }

    /**
     * Update the field mappings for a Cloud LDAP server.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function updateMappings(string $id, array $data): array
    {
        return $this->http->put("/v1/cloud-ldap/{$id}/mappings", $data)->json();
    }

    /**
     * Test a group or user query against a Cloud LDAP server.
     *
     * @param  array<string,mixed>  $query  Test query parameters.
     * @return array<string,mixed>
     */
    public function testQuery(string $id, array $query): array
    {
        return $this->http->post("/v1/cloud-ldap/{$id}/test-query", $query)->json();
    }
}
