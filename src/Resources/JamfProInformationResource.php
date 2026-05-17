<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Resources;

/**
 * Jamf Pro server information — version, build, and configuration data.
 *
 * No special privileges required.
 */
class JamfProInformationResource extends AbstractResource
{
    /**
     * Retrieve Jamf Pro server information including version and build number.
     *
     * @return array<string,mixed>
     */
    public function get(): array
    {
        return $this->http->get('/v2/jamf-pro-information')->json();
    }
}
