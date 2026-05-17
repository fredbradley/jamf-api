<?php

declare(strict_types=1);

namespace FredBradley\JamfApi;

use FredBradley\JamfApi\Resources\AccountGroupsResource;
use FredBradley\JamfApi\Resources\AccountsResource;
use FredBradley\JamfApi\Resources\ActivationCodeResource;
use FredBradley\JamfApi\Resources\AdvancedMobileDeviceSearchesResource;
use FredBradley\JamfApi\Resources\AdvancedUserContentSearchesResource;
use FredBradley\JamfApi\Resources\ApiIntegrationsResource;
use FredBradley\JamfApi\Resources\ApiRolePrivilegesResource;
use FredBradley\JamfApi\Resources\ApiRolesResource;
use FredBradley\JamfApi\Resources\ApnsClientPushStatusResource;
use FredBradley\JamfApi\Resources\AuthResource;
use FredBradley\JamfApi\Resources\BuildingsResource;
use FredBradley\JamfApi\Resources\CacheSettingsResource;
use FredBradley\JamfApi\Resources\CategoriesResource;
use FredBradley\JamfApi\Resources\CheckInResource;
use FredBradley\JamfApi\Resources\CloudLdapResource;
use FredBradley\JamfApi\Resources\ComputerGroupsResource;
use FredBradley\JamfApi\Resources\ComputerInventoryResource;
use FredBradley\JamfApi\Resources\ComputerManagementResource;
use FredBradley\JamfApi\Resources\ComputerPrestagesResource;
use FredBradley\JamfApi\Resources\DashboardResource;
use FredBradley\JamfApi\Resources\DepartmentsResource;
use FredBradley\JamfApi\Resources\DeviceEnrollmentsResource;
use FredBradley\JamfApi\Resources\EnrollmentCustomizationResource;
use FredBradley\JamfApi\Resources\EnrollmentResource;
use FredBradley\JamfApi\Resources\ExtensionAttributesResource;
use FredBradley\JamfApi\Resources\IconsResource;
use FredBradley\JamfApi\Resources\InventoryInformationResource;
use FredBradley\JamfApi\Resources\InventoryPreloadResource;
use FredBradley\JamfApi\Resources\JamfConnectResource;
use FredBradley\JamfApi\Resources\JamfProInformationResource;
use FredBradley\JamfApi\Resources\LdapResource;
use FredBradley\JamfApi\Resources\LocalAdminPasswordResource;
use FredBradley\JamfApi\Resources\ManagedSoftwareUpdatesResource;
use FredBradley\JamfApi\Resources\MdmResource;
use FredBradley\JamfApi\Resources\MobileDeviceAppsResource;
use FredBradley\JamfApi\Resources\MobileDeviceGroupsResource;
use FredBradley\JamfApi\Resources\MobileDeviceManagementCommandsResource;
use FredBradley\JamfApi\Resources\MobileDevicePrestagesResource;
use FredBradley\JamfApi\Resources\MobileDevicesResource;
use FredBradley\JamfApi\Resources\OnboardingResource;
use FredBradley\JamfApi\Resources\PackagesResource;
use FredBradley\JamfApi\Resources\PatchManagementResource;
use FredBradley\JamfApi\Resources\PatchSoftwareTitleConfigurationsResource;
use FredBradley\JamfApi\Resources\PatchTitlesResource;
use FredBradley\JamfApi\Resources\RemoteAdministrationResource;
use FredBradley\JamfApi\Resources\ScriptsResource;
use FredBradley\JamfApi\Resources\SelfServiceBrandingResource;
use FredBradley\JamfApi\Resources\SelfServiceResource;
use FredBradley\JamfApi\Resources\SitesResource;
use FredBradley\JamfApi\Resources\SsoSettingsResource;
use FredBradley\JamfApi\Resources\StartupStatusResource;
use FredBradley\JamfApi\Resources\SupervisionIdentitiesResource;
use FredBradley\JamfApi\Resources\TeacherAppResource;
use FredBradley\JamfApi\Resources\UserEnrollmentsResource;
use FredBradley\JamfApi\Resources\VolumePurchasingLocationsResource;
use FredBradley\JamfApi\Resources\VolumePurchasingSubscriptionsResource;
use FredBradley\JamfApi\Resources\WebhooksResource;

/**
 * The main Jamf Pro API client.
 *
 * Provides access to all Jamf Pro API resource groups as lazily-instantiated
 * typed resource objects. Each resource method returns the same instance on
 * repeated calls (cached via null coalescing assignment).
 *
 * Usage:
 * ```php
 * // Via the service container
 * $client = app(JamfClient::class);
 *
 * // Via the Facade
 * use FredBradley\JamfApi\Facades\Jamf;
 * Jamf::computerInventory()->list(filter: 'general.name=="MacBook*"');
 *
 * // Via direct instantiation
 * $client = new JamfClient($httpClient);
 * ```
 */
final class JamfClient
{
    public function __construct(
        private readonly JamfHttpClient $http,
    ) {}

    // -------------------------------------------------------------------------
    // Authentication & User Management
    // -------------------------------------------------------------------------

    /** Authentication token management. */
    public function auth(): AuthResource
    {
        return new AuthResource($this->http);
    }

    /** Jamf Pro user accounts (CRUD + pagination). */
    public function accounts(): AccountsResource
    {
        return new AccountsResource($this->http);
    }

    /** Jamf Pro account groups (read-only). */
    public function accountGroups(): AccountGroupsResource
    {
        return new AccountGroupsResource($this->http);
    }

    /** API roles — named privilege sets for API integrations. */
    public function apiRoles(): ApiRolesResource
    {
        return new ApiRolesResource($this->http);
    }

    /** API role privileges — available privilege strings. */
    public function apiRolePrivileges(): ApiRolePrivilegesResource
    {
        return new ApiRolePrivilegesResource($this->http);
    }

    /** API integrations — OAuth 2.0 clients (CRUD + credential generation). */
    public function apiIntegrations(): ApiIntegrationsResource
    {
        return new ApiIntegrationsResource($this->http);
    }

    /** LDAP server search and listing. */
    public function ldap(): LdapResource
    {
        return new LdapResource($this->http);
    }

    /** Cloud LDAP (Cloud Identity Provider) configuration. */
    public function cloudLdap(): CloudLdapResource
    {
        return new CloudLdapResource($this->http);
    }

    /** Single Sign-On (SSO) / SAML settings and certificate management. */
    public function ssoSettings(): SsoSettingsResource
    {
        return new SsoSettingsResource($this->http);
    }

    // -------------------------------------------------------------------------
    // Computer Management
    // -------------------------------------------------------------------------

    /** Computer inventory — the v2 paginated, filterable computer list. */
    public function computerInventory(): ComputerInventoryResource
    {
        return new ComputerInventoryResource($this->http);
    }

    /** Computer prestages — automated Mac enrollment profiles. */
    public function computerPrestages(): ComputerPrestagesResource
    {
        return new ComputerPrestagesResource($this->http);
    }

    /** Computer groups — smart and static groups. */
    public function computerGroups(): ComputerGroupsResource
    {
        return new ComputerGroupsResource($this->http);
    }

    /** Computer management summary (applied policies, profiles, etc.). */
    public function computerManagement(): ComputerManagementResource
    {
        return new ComputerManagementResource($this->http);
    }

    // -------------------------------------------------------------------------
    // Mobile Device Management
    // -------------------------------------------------------------------------

    /** Mobile device inventory — iOS, iPadOS, tvOS, watchOS devices. */
    public function mobileDevices(): MobileDevicesResource
    {
        return new MobileDevicesResource($this->http);
    }

    /** Mobile device prestages — automated iOS/iPadOS/tvOS enrollment profiles. */
    public function mobileDevicePrestages(): MobileDevicePrestagesResource
    {
        return new MobileDevicePrestagesResource($this->http);
    }

    /** Mobile device groups — smart and static groups. */
    public function mobileDeviceGroups(): MobileDeviceGroupsResource
    {
        return new MobileDeviceGroupsResource($this->http);
    }

    /** Mobile device managed apps. */
    public function mobileDeviceApps(): MobileDeviceAppsResource
    {
        return new MobileDeviceAppsResource($this->http);
    }

    /** Mobile device MDM commands. */
    public function mobileDeviceManagementCommands(): MobileDeviceManagementCommandsResource
    {
        return new MobileDeviceManagementCommandsResource($this->http);
    }

    /** Advanced mobile device searches (saved complex criteria). */
    public function advancedMobileDeviceSearches(): AdvancedMobileDeviceSearchesResource
    {
        return new AdvancedMobileDeviceSearchesResource($this->http);
    }

    // -------------------------------------------------------------------------
    // Enrollment
    // -------------------------------------------------------------------------

    /** Enrollment settings (MDM profiles, user-initiated enrollment config). */
    public function enrollment(): EnrollmentResource
    {
        return new EnrollmentResource($this->http);
    }

    /** Enrollment customization — Setup Assistant workflow branding. */
    public function enrollmentCustomization(): EnrollmentCustomizationResource
    {
        return new EnrollmentCustomizationResource($this->http);
    }

    /** Apple Device Enrollment Program (DEP/ADE) instances. */
    public function deviceEnrollments(): DeviceEnrollmentsResource
    {
        return new DeviceEnrollmentsResource($this->http);
    }

    /** User-initiated enrollment sessions. */
    public function userEnrollments(): UserEnrollmentsResource
    {
        return new UserEnrollmentsResource($this->http);
    }

    /** APNS push notification status — devices with disabled notifications. */
    public function apnsClientPushStatus(): ApnsClientPushStatusResource
    {
        return new ApnsClientPushStatusResource($this->http);
    }

    // -------------------------------------------------------------------------
    // Configuration Items
    // -------------------------------------------------------------------------

    /** Scripts — shell/Python scripts deployed via policies. */
    public function scripts(): ScriptsResource
    {
        return new ScriptsResource($this->http);
    }

    /** Packages — installer packages deployed via policies. */
    public function packages(): PackagesResource
    {
        return new PackagesResource($this->http);
    }

    /** Categories — organisational labels for scripts, packages, policies, etc. */
    public function categories(): CategoriesResource
    {
        return new CategoriesResource($this->http);
    }

    /** Departments. */
    public function departments(): DepartmentsResource
    {
        return new DepartmentsResource($this->http);
    }

    /** Buildings. */
    public function buildings(): BuildingsResource
    {
        return new BuildingsResource($this->http);
    }

    /** Sites — partitioned administration zones. */
    public function sites(): SitesResource
    {
        return new SitesResource($this->http);
    }

    /** Icons — upload and download icon images. */
    public function icons(): IconsResource
    {
        return new IconsResource($this->http);
    }

    /** Webhooks — HTTP callbacks for Jamf Pro events. */
    public function webhooks(): WebhooksResource
    {
        return new WebhooksResource($this->http);
    }

    // -------------------------------------------------------------------------
    // Patch Management
    // -------------------------------------------------------------------------

    /** Patch management global settings and disclaimer. */
    public function patchManagement(): PatchManagementResource
    {
        return new PatchManagementResource($this->http);
    }

    /** Patch titles — the catalogue of patchable software. */
    public function patchTitles(): PatchTitlesResource
    {
        return new PatchTitlesResource($this->http);
    }

    /** Patch software title configurations — the deployable patch policies. */
    public function patchSoftwareTitleConfigurations(): PatchSoftwareTitleConfigurationsResource
    {
        return new PatchSoftwareTitleConfigurationsResource($this->http);
    }

    // -------------------------------------------------------------------------
    // Inventory & Extension
    // -------------------------------------------------------------------------

    /** Inventory preload — pre-populate device data before enrollment. */
    public function inventoryPreload(): InventoryPreloadResource
    {
        return new InventoryPreloadResource($this->http);
    }

    /** Inventory information — summary counts of managed devices. */
    public function inventoryInformation(): InventoryInformationResource
    {
        return new InventoryInformationResource($this->http);
    }

    /** Extension attributes — custom inventory fields for computers and mobile devices. */
    public function extensionAttributes(): ExtensionAttributesResource
    {
        return new ExtensionAttributesResource($this->http);
    }

    // -------------------------------------------------------------------------
    // Settings & Configuration
    // -------------------------------------------------------------------------

    /** Activation code and organisation name management. */
    public function activationCode(): ActivationCodeResource
    {
        return new ActivationCodeResource($this->http);
    }

    /** Computer check-in frequency settings. */
    public function checkIn(): CheckInResource
    {
        return new CheckInResource($this->http);
    }

    /** Self Service app settings. */
    public function selfService(): SelfServiceResource
    {
        return new SelfServiceResource($this->http);
    }

    /** Self Service branding — iOS and macOS icon/colour customisation. */
    public function selfServiceBranding(): SelfServiceBrandingResource
    {
        return new SelfServiceBrandingResource($this->http);
    }

    /** Onboarding flow configuration. */
    public function onboarding(): OnboardingResource
    {
        return new OnboardingResource($this->http);
    }

    /** Jamf Pro cache settings. */
    public function cacheSettings(): CacheSettingsResource
    {
        return new CacheSettingsResource($this->http);
    }

    /** Dashboard summary data. */
    public function dashboard(): DashboardResource
    {
        return new DashboardResource($this->http);
    }

    /** Jamf Pro server version and build information. */
    public function jamfProInformation(): JamfProInformationResource
    {
        return new JamfProInformationResource($this->http);
    }

    /**
     * Startup/initialization status (no authentication required).
     *
     * Poll this endpoint to wait until Jamf Pro is ready.
     */
    public function startupStatus(): StartupStatusResource
    {
        return new StartupStatusResource($this->http);
    }

    // -------------------------------------------------------------------------
    // Security & Identity
    // -------------------------------------------------------------------------

    /** Supervision identities — Apple Configurator 2 certificates. */
    public function supervisionIdentities(): SupervisionIdentitiesResource
    {
        return new SupervisionIdentitiesResource($this->http);
    }

    /** Local administrator password (LAPS) management. */
    public function localAdminPassword(): LocalAdminPasswordResource
    {
        return new LocalAdminPasswordResource($this->http);
    }

    // -------------------------------------------------------------------------
    // Volume Purchasing (VPP)
    // -------------------------------------------------------------------------

    /** Volume Purchasing locations — Apple Business/School Manager token connections. */
    public function volumePurchasingLocations(): VolumePurchasingLocationsResource
    {
        return new VolumePurchasingLocationsResource($this->http);
    }

    /** Volume Purchasing subscriptions — VPP event notification subscriptions. */
    public function volumePurchasingSubscriptions(): VolumePurchasingSubscriptionsResource
    {
        return new VolumePurchasingSubscriptionsResource($this->http);
    }

    // -------------------------------------------------------------------------
    // MDM & Updates
    // -------------------------------------------------------------------------

    /** MDM command sending and listing. */
    public function mdm(): MdmResource
    {
        return new MdmResource($this->http);
    }

    /** Managed software update plans and available updates. */
    public function managedSoftwareUpdates(): ManagedSoftwareUpdatesResource
    {
        return new ManagedSoftwareUpdatesResource($this->http);
    }

    /** Remote administration — Team Viewer integration. */
    public function remoteAdministration(): RemoteAdministrationResource
    {
        return new RemoteAdministrationResource($this->http);
    }

    // -------------------------------------------------------------------------
    // Additional Features
    // -------------------------------------------------------------------------

    /** Jamf Connect configuration and deployment. */
    public function jamfConnect(): JamfConnectResource
    {
        return new JamfConnectResource($this->http);
    }

    /** Teacher app settings. */
    public function teacherApp(): TeacherAppResource
    {
        return new TeacherAppResource($this->http);
    }

    /** Advanced user content searches. */
    public function advancedUserContentSearches(): AdvancedUserContentSearchesResource
    {
        return new AdvancedUserContentSearchesResource($this->http);
    }
}
