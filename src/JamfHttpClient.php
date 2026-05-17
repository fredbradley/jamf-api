<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi;

use Cranleigh\JamfApi\Auth\Contracts\AuthenticatorInterface;
use Cranleigh\JamfApi\Exceptions\AuthenticationException;
use Cranleigh\JamfApi\Exceptions\ForbiddenException;
use Cranleigh\JamfApi\Exceptions\JamfException;
use Cranleigh\JamfApi\Exceptions\NotFoundException;
use Cranleigh\JamfApi\Exceptions\RateLimitException;
use Cranleigh\JamfApi\Exceptions\ServerException;
use Cranleigh\JamfApi\Exceptions\UnprocessableEntityException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * Internal HTTP client that wraps the Laravel HTTP Facade.
 *
 * Handles authentication header injection, token refresh, query parameter
 * sanitisation, and maps HTTP error status codes to typed exceptions.
 *
 * @internal This class is not part of the public API and may change without notice.
 */
final class JamfHttpClient
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly AuthenticatorInterface $auth,
        private readonly int $timeout = 30,
    ) {}

    /**
     * Send a GET request.
     *
     * @param  array<string,mixed>  $query
     */
    public function get(string $path, array $query = []): Response
    {
        return $this->request('GET', $path, query: $query);
    }

    /**
     * Send a POST request with a JSON body.
     *
     * @param  array<string,mixed>  $body
     */
    public function post(string $path, array $body = []): Response
    {
        return $this->request('POST', $path, body: $body);
    }

    /**
     * Send a PUT request with a JSON body.
     *
     * @param  array<string,mixed>  $body
     */
    public function put(string $path, array $body = []): Response
    {
        return $this->request('PUT', $path, body: $body);
    }

    /**
     * Send a PATCH request with a JSON body.
     *
     * @param  array<string,mixed>  $body
     */
    public function patch(string $path, array $body = []): Response
    {
        return $this->request('PATCH', $path, body: $body);
    }

    /**
     * Send a DELETE request.
     */
    public function delete(string $path): Response
    {
        return $this->request('DELETE', $path);
    }

    /**
     * Send a multipart/form-data POST request (for file uploads).
     *
     * Each part is an array with 'name', 'contents', and optionally 'filename'.
     *
     * @param  array<int, array{name: string, contents: mixed, filename?: string}>  $parts
     */
    public function postMultipart(string $path, array $parts): Response
    {
        $this->ensureToken();

        $pending = Http::withHeaders([
            'Authorization' => $this->auth->getAuthorizationHeader(),
            'Accept' => 'application/json',
        ])->timeout($this->timeout)->baseUrl($this->baseUrl);

        foreach ($parts as $part) {
            if (isset($part['filename'])) {
                $pending = $pending->attach($part['name'], $part['contents'], $part['filename']);
            } else {
                $pending = $pending->attach($part['name'], $part['contents']);
            }
        }

        $response = $pending->post('/api'.$path);

        return $this->throwIfFailed($response);
    }

    /**
     * Core request dispatcher.
     *
     * @param  array<string,mixed>  $query
     * @param  array<string,mixed>  $body
     */
    private function request(
        string $method,
        string $path,
        array $query = [],
        array $body = [],
    ): Response {
        $this->ensureToken();

        $pending = Http::withHeaders([
            'Authorization' => $this->auth->getAuthorizationHeader(),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])
            ->timeout($this->timeout)
            ->baseUrl($this->baseUrl);

        $cleanQuery = $this->sanitiseQuery($query);

        $response = match ($method) {
            'GET' => $pending->get('/api'.$path, $cleanQuery ?: null),
            'POST' => $pending->post('/api'.$path, $body),
            'PUT' => $pending->put('/api'.$path, $body),
            'PATCH' => $pending->patch('/api'.$path, $body),
            'DELETE' => $pending->delete('/api'.$path),
            default => throw new \InvalidArgumentException("Unsupported HTTP method: {$method}"),
        };

        return $this->throwIfFailed($response);
    }

    /**
     * Refresh the auth token if it is near expiry.
     */
    private function ensureToken(): void
    {
        if ($this->auth->shouldRefresh()) {
            $this->auth->refresh();
        }
    }

    /**
     * Remove null, empty string, and empty array values from query parameters.
     * Also serialises array values as comma-separated strings for RSQL sort params.
     *
     * @param  array<string,mixed>  $query
     * @return array<string,mixed>
     */
    private function sanitiseQuery(array $query): array
    {
        $clean = [];

        foreach ($query as $key => $value) {
            if ($value === null || $value === '' || $value === []) {
                continue;
            }

            // The Jamf API accepts repeated sort/filter params; pass as array directly.
            $clean[$key] = $value;
        }

        return $clean;
    }

    /**
     * Map HTTP error status codes to typed exceptions.
     *
     * @throws JamfException
     */
    private function throwIfFailed(Response $response): Response
    {
        if ($response->successful()) {
            return $response;
        }

        $status = $response->status();
        $body = $response->json() ?? [];
        $errors = is_array($body) ? $body : [];
        $detail = $errors['detail'] ?? $errors['message'] ?? $errors['error'] ?? 'Unknown error';

        throw match (true) {
            $status === 401 => new AuthenticationException("Jamf Pro API: Unauthorized — {$detail}", $status, $errors),
            $status === 403 => new ForbiddenException("Jamf Pro API: Forbidden — {$detail}", $status, $errors),
            $status === 404 => new NotFoundException("Jamf Pro API: Not Found — {$detail}", $status, $errors),
            $status === 422 => new UnprocessableEntityException("Jamf Pro API: Validation failed — {$detail}", $status, $errors),
            $status === 429 => new RateLimitException("Jamf Pro API: Rate limit exceeded — {$detail}", $status, $errors),
            $status >= 500 => new ServerException("Jamf Pro API: Server error {$status} — {$detail}", $status, $errors),
            default => new JamfException("Jamf Pro API: HTTP {$status} — {$detail}", $status, $errors),
        };
    }
}
