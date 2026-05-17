<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Facades;

use FredBradley\JamfApi\JamfClient;
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
use Illuminate\Support\Facades\Facade;

/**
 * Facade for the Jamf Pro API client.
 *
 * All methods proxy to the underlying {@see JamfClient} singleton.
 * The full method list is documented below for IDE autocompletion.
 *
 * @method static AuthResource auth()
 * @method static AccountsResource accounts()
 * @method static AccountGroupsResource accountGroups()
 * @method static ApiRolesResource apiRoles()
 * @method static ApiRolePrivilegesResource apiRolePrivileges()
 * @method static ApiIntegrationsResource apiIntegrations()
 * @method static LdapResource ldap()
 * @method static CloudLdapResource cloudLdap()
 * @method static SsoSettingsResource ssoSettings()
 * @method static ComputerInventoryResource computerInventory()
 * @method static ComputerPrestagesResource computerPrestages()
 * @method static ComputerGroupsResource computerGroups()
 * @method static ComputerManagementResource computerManagement()
 * @method static MobileDevicesResource mobileDevices()
 * @method static MobileDevicePrestagesResource mobileDevicePrestages()
 * @method static MobileDeviceGroupsResource mobileDeviceGroups()
 * @method static MobileDeviceAppsResource mobileDeviceApps()
 * @method static MobileDeviceManagementCommandsResource mobileDeviceManagementCommands()
 * @method static AdvancedMobileDeviceSearchesResource advancedMobileDeviceSearches()
 * @method static EnrollmentResource enrollment()
 * @method static EnrollmentCustomizationResource enrollmentCustomization()
 * @method static DeviceEnrollmentsResource deviceEnrollments()
 * @method static UserEnrollmentsResource userEnrollments()
 * @method static ApnsClientPushStatusResource apnsClientPushStatus()
 * @method static ScriptsResource scripts()
 * @method static PackagesResource packages()
 * @method static CategoriesResource categories()
 * @method static DepartmentsResource departments()
 * @method static BuildingsResource buildings()
 * @method static SitesResource sites()
 * @method static IconsResource icons()
 * @method static WebhooksResource webhooks()
 * @method static PatchManagementResource patchManagement()
 * @method static PatchTitlesResource patchTitles()
 * @method static PatchSoftwareTitleConfigurationsResource patchSoftwareTitleConfigurations()
 * @method static InventoryPreloadResource inventoryPreload()
 * @method static InventoryInformationResource inventoryInformation()
 * @method static ExtensionAttributesResource extensionAttributes()
 * @method static ActivationCodeResource activationCode()
 * @method static CheckInResource checkIn()
 * @method static SelfServiceResource selfService()
 * @method static SelfServiceBrandingResource selfServiceBranding()
 * @method static OnboardingResource onboarding()
 * @method static CacheSettingsResource cacheSettings()
 * @method static DashboardResource dashboard()
 * @method static JamfProInformationResource jamfProInformation()
 * @method static StartupStatusResource startupStatus()
 * @method static SupervisionIdentitiesResource supervisionIdentities()
 * @method static LocalAdminPasswordResource localAdminPassword()
 * @method static VolumePurchasingLocationsResource volumePurchasingLocations()
 * @method static VolumePurchasingSubscriptionsResource volumePurchasingSubscriptions()
 * @method static MdmResource mdm()
 * @method static ManagedSoftwareUpdatesResource managedSoftwareUpdates()
 * @method static RemoteAdministrationResource remoteAdministration()
 * @method static JamfConnectResource jamfConnect()
 * @method static TeacherAppResource teacherApp()
 * @method static AdvancedUserContentSearchesResource advancedUserContentSearches()
 *
 * @see JamfClient
 */
class Jamf extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'jamf';
    }
}
