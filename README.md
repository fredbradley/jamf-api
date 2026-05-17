# cranleigh/jamf-api

A fully typed, fluent PHP 8.5+ wrapper for the [Jamf Pro REST API](https://developer.jamf.com/jamf-pro/docs), built for Laravel 12/13 using the HTTP Facade.

- Covers **all major Jamf Pro API resource groups** (computers, mobile devices, scripts, packages, policies, patch management, enrollment, VPP, and more)
- **Two authentication methods**: bearer token (username/password) or OAuth 2.0 client credentials — with automatic token caching and refresh
- **Strongly typed responses** — every endpoint returns a PHP 8.5 `readonly` DTO or a typed `Page<T>` paginator
- **Typed exceptions** mapped to HTTP status codes — `NotFoundException`, `ForbiddenException`, etc.
- Full **IDE autocompletion** via PHPDoc on every method and the `Jamf` facade

---

## Requirements

| Requirement | Version |
|---|---|
| PHP | ^8.5 |
| Laravel | 12 or 13 |

---

## Installation

```bash
composer require cranleigh/jamf-api
```

The service provider and facade are registered automatically via Laravel package discovery.

Publish the config file:

```bash
php artisan vendor:publish --tag=jamf-config
```

---

## Configuration

Add the following to your `.env` file:

```env
JAMF_BASE_URL=https://yourorg.jamfcloud.com

# Authentication method: "token" or "oauth"
JAMF_AUTH=token

# For token auth (username + password)
JAMF_USERNAME=your-api-user
JAMF_PASSWORD=your-api-password

# For OAuth (client credentials)
JAMF_CLIENT_ID=your-client-id
JAMF_CLIENT_SECRET=your-client-secret

# Optional
JAMF_TIMEOUT=30
JAMF_CACHE_DRIVER=        # defaults to your app's default cache store
```

### Authentication methods

**Token authentication** (`JAMF_AUTH=token`)

Exchanges a Jamf Pro username and password for a bearer token via `POST /api/v1/auth/token`. The token is cached and automatically renewed using the keep-alive endpoint before it expires. Best for user accounts with defined API privileges.

**OAuth 2.0 client credentials** (`JAMF_AUTH=oauth`)

Uses a Jamf Pro API integration's `client_id` and `client_secret` to obtain an access token via `POST /api/oauth/token`. This is the recommended approach for machine-to-machine integrations. Create an API integration in Jamf Pro under **Settings → API roles and clients**, assign it an API role, and copy the generated credentials.

---

## Usage

### Via the Facade

```php
use Cranleigh\JamfApi\Facades\Jamf;

$page = Jamf::computerInventory()->list();
```

### Via dependency injection

```php
use Cranleigh\JamfApi\JamfClient;

class DeviceController extends Controller
{
    public function __construct(private readonly JamfClient $jamf) {}

    public function index(): JsonResponse
    {
        $page = $this->jamf->computerInventory()->list();
        return response()->json($page->results);
    }
}
```

---

## Pagination

Endpoints that return lists use the `Page<T>` wrapper:

```php
use Cranleigh\JamfApi\Facades\Jamf;

$page = Jamf::computerInventory()->list(
    page:     0,        // zero-based page index
    pageSize: 50,       // results per page
);

$page->results;         // list<ComputerSummary>
$page->totalCount;      // int — total records across all pages
$page->totalPages();    // int
$page->hasMorePages();  // bool
$page->nextPage();      // ?int — null on the last page
$page->isFirstPage();   // bool
$page->isLastPage();    // bool
$page->count();         // number of results on this page
```

Iterate through all pages:

```php
$page = 0;

do {
    $result = Jamf::mobileDevices()->list(page: $page, pageSize: 100);

    foreach ($result->results as $device) {
        // $device is a typed MobileDeviceSummary
        echo $device->name . ' — ' . $device->serialNumber;
    }

    $page++;
} while ($result->hasMorePages());
```

---

## Filtering and Sorting

List endpoints support RSQL-style filter strings and sort fields:

```php
// Find all MacBooks managed by Jamf Pro
$page = Jamf::computerInventory()->list(
    filter: 'general.name=="MacBook*"',
    sort:   ['general.name:asc'],
);

// Find scripts whose name starts with "Deploy"
$page = Jamf::scripts()->list(
    filter: 'name=="Deploy*"',
    sort:   ['name:asc', 'modificationDate:desc'],
);

// Find mobile devices on a specific OS version
$page = Jamf::mobileDevices()->list(
    filter: 'osVersion=="18.*"',
);
```

Use the `SortOrder` enum to build sort strings safely:

```php
use Cranleigh\JamfApi\Enums\SortOrder;

$sort = [
    SortOrder::Asc->for('general.name'),
    SortOrder::Desc->for('general.lastContactTime'),
];

$page = Jamf::computerInventory()->list(sort: $sort);
```

---

## Computer Inventory

```php
use Cranleigh\JamfApi\Enums\ComputerInventorySection;
use Cranleigh\JamfApi\Facades\Jamf;

// List computers (general fields only by default)
$page = Jamf::computerInventory()->list(
    filter: 'general.managed==true',
    sort:   ['general.name:asc'],
);

// $page->results is list<ComputerSummary>
foreach ($page->results as $computer) {
    echo $computer->id;
    echo $computer->name;
    echo $computer->serialNumber;
    echo $computer->osVersion;
    echo $computer->username;
    echo $computer->email;
}

// Retrieve a specific computer with additional inventory sections
$computer = Jamf::computerInventory()->find('123', section: [
    ComputerInventorySection::Hardware->value,
    ComputerInventorySection::Storage->value,
    ComputerInventorySection::Applications->value,
]);

// $computer is a ComputerDetail with hardware/storage/applications arrays populated
$computer->hardware['model'];
$computer->storage;

// Full detail (all sections)
$detail = Jamf::computerInventory()->detail('123');

// Partial update
$updated = Jamf::computerInventory()->patch('123', [
    'userAndLocation' => ['username' => 'jsmith', 'email' => 'j.smith@example.com'],
]);

// Delete
Jamf::computerInventory()->delete('123');
Jamf::computerInventory()->deleteMultiple(['1', '2', '3']);
```

---

## Mobile Devices

```php
$page = Jamf::mobileDevices()->list(
    filter: 'managed==true',
    sort:   ['name:asc'],
);

foreach ($page->results as $device) {
    // $device is a MobileDeviceSummary
    echo $device->name;
    echo $device->serialNumber;
    echo $device->osVersion;
    echo $device->managed ? 'managed' : 'unmanaged';
}

// Full detail
$detail = Jamf::mobileDevices()->detail('456');

// Partial update
Jamf::mobileDevices()->patch('456', [
    'location' => ['username' => 'jsmith'],
]);
```

---

## Scripts

```php
// List
$page = Jamf::scripts()->list(filter: 'name=="Deploy*"');

// Find
$script = Jamf::scripts()->find('10');
echo $script->name;
echo $script->scriptContents;

// Create
$script = Jamf::scripts()->create([
    'name'           => 'Install Homebrew',
    'priority'       => 'AFTER',
    'scriptContents' => '#!/bin/bash' . PHP_EOL . '/bin/bash -c "$(curl -fsSL ...)"',
]);

// Update
Jamf::scripts()->update('10', ['name' => 'Install Homebrew v2']);

// Delete
Jamf::scripts()->delete('10');

// History
$history = Jamf::scripts()->historyFor('10');
foreach ($history->results as $note) {
    echo $note->username . ': ' . $note->note;
}
```

---

## Packages

```php
$page = Jamf::packages()->list();

$package = Jamf::packages()->find('5');
echo $package->packageName;
echo $package->fileName;
echo $package->rebootRequired ? 'reboot required' : '';

// Create a package record (file upload to distribution point is separate)
Jamf::packages()->create([
    'packageName' => 'Google Chrome 124.pkg',
    'fileName'    => 'Google Chrome 124.pkg',
    'categoryId'  => '3',
]);

// History
Jamf::packages()->historyFor('5');
```

---

## Prestages (Automated Device Enrollment)

```php
// Computer prestages
$page = Jamf::computerPrestages()->list();

$prestage = Jamf::computerPrestages()->find('1');
echo $prestage->displayName;
echo $prestage->defaultPrestage ? 'default' : '';

// View scope (assigned serial numbers)
$scope = Jamf::computerPrestages()->scope('1');

// Add devices to scope
Jamf::computerPrestages()->addToScope('1', ['C02XG123ABCD', 'C02XH456EFGH'], $prestage->versionLock);

// Remove from scope
Jamf::computerPrestages()->removeFromScope('1', ['C02XG123ABCD'], $prestage->versionLock);

// Mobile device prestages work the same way
$page = Jamf::mobileDevicePrestages()->list();
Jamf::mobileDevicePrestages()->addToScope('2', ['DLXVF123ABCD'], $versionLock);
```

---

## Device Enrollments (DEP/ADE)

```php
// List Apple Business Manager / School Manager connections
$page = Jamf::deviceEnrollments()->list();

// Trigger a sync
Jamf::deviceEnrollments()->sync('1');

// Check latest sync status
$sync = Jamf::deviceEnrollments()->latestSync('1');

// List enrolled devices
$devices = Jamf::deviceEnrollments()->devices('1');
```

---

## Webhooks

```php
// List
$page = Jamf::webhooks()->list();

// Create
$webhook = Jamf::webhooks()->create([
    'name'        => 'Device Enrolled',
    'enabled'     => true,
    'url'         => 'https://yourapp.example.com/jamf/webhook',
    'contentType' => 'JSON',
    'event'       => 'DeviceEnrolled',
]);

// Update
Jamf::webhooks()->update($webhook->id, ['enabled' => false]);

// Delete
Jamf::webhooks()->delete($webhook->id);
```

---

## API Roles & Integrations

```php
// List available privilege strings
$privileges = Jamf::apiRolePrivileges()->list();
$matches    = Jamf::apiRolePrivileges()->search('Read Computers');

// Create an API role
$role = Jamf::apiRoles()->create('Read-Only Access', [
    'Read Computers',
    'Read Mobile Devices',
    'Read Scripts',
]);

// Create an API integration and generate OAuth credentials
$integration = Jamf::apiIntegrations()->create([
    'displayName'               => 'My Laravel App',
    'enabled'                   => true,
    'accessTokenLifetimeSeconds' => 1800,
    'apiRoleIds'                => [$role->id],
]);

$credentials = Jamf::apiIntegrations()->generateClientCredentials($integration->id);
// $credentials['clientId'] and $credentials['clientSecret'] — store the secret securely, it cannot be retrieved again
```

---

## Patch Management

```php
// Accept the disclaimer (required once before enabling patch management)
Jamf::patchManagement()->acceptDisclaimer();

// Enable patch management
Jamf::patchManagement()->save(['enabled' => true]);

// List patchable software titles
$page = Jamf::patchTitles()->list(filter: 'name=="Google Chrome*"');

// Create a patch software title configuration
$config = Jamf::patchSoftwareTitleConfigurations()->create([
    'displayName'    => 'Google Chrome Updates',
    'softwareTitleId' => '42',
    'categoryId'     => '3',
]);

// View the deployment dashboard
$dashboard = Jamf::patchSoftwareTitleConfigurations()->dashboard($config->id);

// Export a patch report as CSV
$csv = Jamf::patchSoftwareTitleConfigurations()->exportReport($config->id);
```

---

## Inventory Preload

```php
// Download the CSV template
$template = Jamf::inventoryPreload()->csvTemplate();
file_put_contents('/tmp/preload-template.csv', $template);

// Import records from CSV
$result = Jamf::inventoryPreload()->importCsv(file_get_contents('/tmp/devices.csv'));

// Manage individual records
$page   = Jamf::inventoryPreload()->list();
$record = Jamf::inventoryPreload()->find('1');

Jamf::inventoryPreload()->create([
    'serialNumber' => 'C02XG123ABCD',
    'deviceType'   => 'Computer',
    'username'     => 'jsmith',
    'department'   => 'IT',
]);

Jamf::inventoryPreload()->deleteAll(); // remove all preload records
```

---

## Local Admin Password (LAPS)

```php
// Get the current local admin password for a device
$result = Jamf::localAdminPassword()->getPassword(
    clientManagementId: 'abc-123',
    username: 'admin'
);

// Rotate (regenerate) the password
Jamf::localAdminPassword()->rotate('abc-123', 'admin');

// List all LAPS accounts across all devices
$page = Jamf::localAdminPassword()->allAccounts(filter: 'deviceName=="MacBook*"');
```

---

## MDM Commands

```php
use Cranleigh\JamfApi\Enums\MdmCommandType;

// Send a blank push to trigger check-in
Jamf::mdm()->send([
    'clientManagementIds' => ['device-mgmt-id-1', 'device-mgmt-id-2'],
    'commandType'         => MdmCommandType::BlankPush->value,
]);

// Lock a device
Jamf::mdm()->send([
    'clientManagementIds' => ['device-mgmt-id-1'],
    'commandType'         => MdmCommandType::DeviceLock->value,
    'pin'                 => '123456', // required for Mac
]);

// Redeploy the Jamf management framework to a computer
Jamf::mdm()->redeployManagementFramework('computer-mgmt-id');
```

---

## Volume Purchasing (VPP)

```php
// List Apple Business Manager content token connections
$page = Jamf::volumePurchasingLocations()->list();

// Revoke all licenses for a location (e.g. before deleting)
Jamf::volumePurchasingLocations()->revokeLicenses('1');

// Manage VPP event subscriptions
$page = Jamf::volumePurchasingSubscriptions()->list();
Jamf::volumePurchasingSubscriptions()->create([
    'name'    => 'VPP License Assigned',
    'enabled' => true,
]);
```

---

## History

Any resource that supports history exposes a `history()` or `historyFor()` method:

```php
// Global history (for resources like departments, buildings)
$history = Jamf::departments()->globalHistory(sort: ['date:desc']);

// Per-record history (for scripts, packages, webhooks, prestages, etc.)
$history = Jamf::scripts()->historyFor('10', pageSize: 50);
$history = Jamf::computerPrestages()->historyFor('1');
$history = Jamf::webhooks()->historyFor('5');

foreach ($history->results as $note) {
    // $note is a HistoryNote
    echo $note->date . ' — ' . $note->username . ': ' . $note->note;
}

// Add a note
Jamf::scripts()->historyFor('10'); // set current context first
Jamf::scripts()->addHistoryNote('Updated script for macOS Sequoia compatibility');
```

---

## Error Handling

All API errors are thrown as typed exceptions that extend `JamfException`:

| HTTP Status | Exception |
|---|---|
| 401 | `AuthenticationException` |
| 403 | `ForbiddenException` |
| 404 | `NotFoundException` |
| 422 | `UnprocessableEntityException` |
| 429 | `RateLimitException` |
| 5xx | `ServerException` |

```php
use Cranleigh\JamfApi\Exceptions\NotFoundException;
use Cranleigh\JamfApi\Exceptions\JamfException;
use Cranleigh\JamfApi\Facades\Jamf;

try {
    $computer = Jamf::computerInventory()->find('999');
} catch (NotFoundException $e) {
    // Computer does not exist
    logger()->warning('Computer not found', ['id' => '999']);
} catch (JamfException $e) {
    // Catch-all for any other Jamf API error
    logger()->error('Jamf API error', [
        'status' => $e->getHttpStatus(),
        'errors' => $e->getErrors(),
    ]);
}
```

---

## Available Resources

| Facade method | Description |
|---|---|
| `auth()` | Token management (obtain, keep-alive, invalidate) |
| `accounts()` | User accounts (CRUD) |
| `accountGroups()` | Account groups (read) |
| `apiRoles()` | API roles (CRUD) |
| `apiRolePrivileges()` | Available API privilege strings |
| `apiIntegrations()` | OAuth API integrations (CRUD + credential generation) |
| `ldap()` | LDAP server listing and group search |
| `cloudLdap()` | Cloud Identity Provider (LDAP) configuration |
| `ssoSettings()` | SAML SSO settings and certificate management |
| `computerInventory()` | v2 computer inventory (list, find, detail, patch, delete) |
| `computerPrestages()` | Mac automated enrollment profiles (CRUD + scope) |
| `computerGroups()` | Smart and static computer groups |
| `computerManagement()` | Applied policies/profiles summary for a computer |
| `mobileDevices()` | Mobile device inventory (list, find, detail, patch) |
| `mobileDevicePrestages()` | iOS/iPadOS/tvOS enrollment profiles (CRUD + scope + syncs) |
| `mobileDeviceGroups()` | Smart and static mobile device groups |
| `mobileDeviceApps()` | Managed apps (CRUD) |
| `mobileDeviceManagementCommands()` | Send MDM commands to mobile devices |
| `advancedMobileDeviceSearches()` | Saved advanced search criteria (CRUD) |
| `enrollment()` | Global enrollment settings (get, save, language messaging) |
| `enrollmentCustomization()` | Setup Assistant branding (CRUD) |
| `deviceEnrollments()` | Apple DEP/ADE instances (CRUD + sync + devices) |
| `userEnrollments()` | User-initiated enrollment sessions |
| `apnsClientPushStatus()` | Devices with disabled APNS push |
| `scripts()` | Shell/Python scripts (CRUD + history) |
| `packages()` | Installer packages (CRUD + history) |
| `categories()` | Categories (CRUD + history) |
| `departments()` | Departments (CRUD + history + CSV export) |
| `buildings()` | Buildings (CRUD + history + CSV export) |
| `sites()` | Sites (list) |
| `icons()` | Icon upload and download |
| `webhooks()` | HTTP event webhooks (CRUD + history) |
| `patchManagement()` | Patch management settings + disclaimer |
| `patchTitles()` | Patchable software title catalogue (list + patches) |
| `patchSoftwareTitleConfigurations()` | Patch policies (CRUD + dashboard + export + history) |
| `inventoryPreload()` | Pre-enrollment device data (CRUD + CSV import/export) |
| `inventoryInformation()` | Device count summary |
| `extensionAttributes()` | Custom inventory fields (computer + mobile device) |
| `activationCode()` | Activation code and organisation name |
| `checkIn()` | Computer check-in frequency settings |
| `selfService()` | Self Service app settings |
| `selfServiceBranding()` | Self Service iOS/macOS branding (CRUD + image upload) |
| `onboarding()` | Onboarding flow configuration |
| `cacheSettings()` | Cache settings |
| `dashboard()` | Dashboard summary data |
| `jamfProInformation()` | Server version and build info |
| `startupStatus()` | Server startup progress (no auth required) |
| `supervisionIdentities()` | Apple Configurator supervision certificates (CRUD + download) |
| `localAdminPassword()` | LAPS — view and rotate local admin passwords |
| `volumePurchasingLocations()` | VPP content token connections (CRUD + revoke) |
| `volumePurchasingSubscriptions()` | VPP event subscriptions (CRUD) |
| `mdm()` | MDM command sending + management framework redeploy |
| `managedSoftwareUpdates()` | OS/software update plans (CRUD) |
| `remoteAdministration()` | Team Viewer remote support sessions |
| `jamfConnect()` | Jamf Connect settings and deployments |
| `teacherApp()` | Teacher app settings |
| `advancedUserContentSearches()` | Advanced user content searches (CRUD) |

---

## Testing

The package uses the Laravel HTTP Facade, so responses can be faked in tests using `Http::fake()`:

```php
use Cranleigh\JamfApi\Facades\Jamf;
use Illuminate\Support\Facades\Http;

Http::fake([
    '*/api/v1/auth/token' => Http::response([
        'token'   => 'fake-token',
        'expires' => now()->addMinutes(30)->toIso8601String(),
    ]),
    '*/api/v2/computers-inventory*' => Http::response([
        'totalCount' => 1,
        'results'    => [
            ['id' => '1', 'udid' => 'ABCD', 'name' => 'Test Mac', 'managed' => true, 'supervised' => true],
        ],
    ]),
]);

$page = Jamf::computerInventory()->list();

expect($page->results[0]->name)->toBe('Test Mac');
```

Run the package's own tests:

```bash
composer test
# or
./vendor/bin/pest
```

---

## License

MIT
