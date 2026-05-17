<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Resources\Concerns\HasHistory;

/**
 * Single Sign-On (SSO) settings.
 *
 * Manage SAML-based SSO configuration, including the identity provider
 * certificate and SSO enablement.
 *
 * Required privilege: Read SSO / Update SSO
 */
class SsoSettingsResource extends AbstractResource
{
    use HasHistory;

    /**
     * Retrieve the current SSO settings.
     *
     * @return array<string,mixed>
     */
    public function get(): array
    {
        return $this->http->get('/v1/sso')->json();
    }

    /**
     * Update SSO settings.
     *
     * @param  array<string,mixed>  $data
     * @return array<string,mixed>
     */
    public function save(array $data): array
    {
        return $this->http->put('/v1/sso', $data)->json();
    }

    /**
     * Retrieve the current SSO identity provider certificate.
     *
     * @return array<string,mixed>
     */
    public function certificate(): array
    {
        return $this->http->get('/v1/sso/cert')->json();
    }

    /**
     * Update the SSO identity provider certificate.
     *
     * @param  array<string,mixed>  $data  Certificate data.
     * @return array<string,mixed>
     */
    public function updateCertificate(array $data): array
    {
        return $this->http->put('/v1/sso/cert', $data)->json();
    }

    /**
     * Delete the current SSO identity provider certificate.
     */
    public function deleteCertificate(): void
    {
        $this->http->delete('/v1/sso/cert');
    }

    /**
     * Download the SSO certificate as a PEM string.
     */
    public function downloadCertificate(): string
    {
        return $this->http->get('/v1/sso/cert/download')->body();
    }

    /**
     * Validate an SSO session token.
     *
     * @param  string  $token  The session token to validate.
     * @return array<string,mixed>
     */
    public function validateToken(string $token): array
    {
        return $this->http->post('/v1/sso/validate', ['token' => $token])->json();
    }

    protected function historyPath(): string
    {
        return '/v1/sso/history';
    }
}
