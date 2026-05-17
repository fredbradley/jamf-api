<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Jamf Pro Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL of your Jamf Pro instance, e.g. https://yourorg.jamfcloud.com
    | Do not include a trailing slash.
    |
    */

    'base_url' => env('JAMF_BASE_URL'),

    /*
    |--------------------------------------------------------------------------
    | Authentication Method
    |--------------------------------------------------------------------------
    |
    | Supported: "token", "oauth"
    |
    | "token"  — Exchanges a username and password for a bearer token via
    |             POST /api/v1/auth/token. The token is cached and kept alive
    |             automatically.
    |
    | "oauth"  — Uses OAuth 2.0 client credentials (client_id / client_secret)
    |             via POST /api/oauth/token. Preferred for API integrations.
    |
    */

    'auth' => env('JAMF_AUTH', 'token'),

    /*
    |--------------------------------------------------------------------------
    | Token Authentication Credentials
    |--------------------------------------------------------------------------
    |
    | Used when auth = "token". These are the credentials of a Jamf Pro user
    | account with the required API privileges.
    |
    */

    'username' => env('JAMF_USERNAME'),
    'password' => env('JAMF_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | OAuth Client Credentials
    |--------------------------------------------------------------------------
    |
    | Used when auth = "oauth". Create an API integration in Jamf Pro to
    | generate a client_id and client_secret.
    |
    */

    'client_id' => env('JAMF_CLIENT_ID'),
    'client_secret' => env('JAMF_CLIENT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Timeout
    |--------------------------------------------------------------------------
    |
    | Timeout in seconds for each HTTP request to the Jamf Pro API.
    |
    */

    'timeout' => (int) env('JAMF_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Cache Driver
    |--------------------------------------------------------------------------
    |
    | The cache store used to persist auth tokens between requests.
    | Defaults to the application's default cache store.
    |
    */

    'cache_driver' => env('JAMF_CACHE_DRIVER', null),

];
