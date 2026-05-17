<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

use Cranleigh\JamfApi\Data\Site\SiteRecord;

/**
 * Jamf Pro sites.
 *
 * Sites are used to partition a Jamf Pro environment for delegated administration.
 * This resource is read-only via the Jamf Pro API.
 *
 * Required privilege: Read Sites
 */
class SitesResource extends AbstractResource
{
    /**
     * List all sites.
     *
     * @return list<SiteRecord>
     */
    public function list(): array
    {
        $results = $this->http->get('/v1/sites')->json('results', []);

        return array_map(SiteRecord::fromArray(...), $results);
    }
}
