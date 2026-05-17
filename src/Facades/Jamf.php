<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Facades;

use Cranleigh\JamfApi\JamfClient;
use Cranleigh\JamfApi\Resources\AccountGroupsResource;
use Cranleigh\JamfApi\Resources\AccountsResource;
use Cranleigh\JamfApi\Resources\ActivationCodeResource;
use Cranleigh\JamfApi\Resources\AdvancedMobileDeviceSearchesResource;
use Cranleigh\JamfApi\Resources\AdvancedUserContentSearchesResource;
use Cranleigh\JamfApi\Resources\ApiIntegrationsResource;
use Cranleigh\JamfApi\Resources\ApiRolePrivilegesResource;
use Cranleigh\JamfApi\Resources\ApiRolesResource;
use Cranleigh\JamfApi\Resources\ApnsClientPushStatusResource;
use Cranleigh\JamfApi\Resources\AuthResource;
use Cranleigh\JamfApi\Resources\BuildingsResource;
use Cranleigh\JamfApi\Resources\CacheSettingsResource;
use Cranleigh\JamfApi\Resources\CategoriesResource;
use Cranleigh\JamfApi\Resources\CheckInResource;
use Cranleigh\JamfApi\Resources\CloudLdapResource;
use Cranleigh\JamfApi\Resources\ComputerGroupsResource;
use Cranleigh\JamfApi\Resources\ComputerInventoryResource;
use Cranleigh\JamfApi\Resources\ComputerManagementResource;
use Cranleigh\JamfApi\Resources\ComputerPrestagesResource;
use Cranleigh\JamfApi\Resources\DashboardResource;
use Cranleigh\JamfApi\Resources\DepartmentsResource;
use Cranleigh\JamfApi\Resources\DeviceEnrollmentsResource;
use Cranleigh\JamfApi\Resources\EnrollmentCustomizationResource;
use Cranleigh\JamfApi\Resources\EnrollmentResource;
use Cranleigh\JamfApi\Resources\ExtensionAttributesResource;
use Cranleigh\JamfApi\Resources\IconsResource;
use Cranleigh\JamfApi\Resources\InventoryInformationResource;
use Cranleigh\JamfApi\Resources\InventoryPreloadResource;
use Cranleigh\JamfApi\Resources\JamfConnectResource;
use Cranleigh\JamfApi\Resources\JamfProInformationResource;
use Cranleigh\JamfApi\Resources\LdapResource;
use Cranleigh\JamfApi\Resources\LocalAdminPasswordResource;
use Cranleigh\JamfApi\Resources\ManagedSoftwareUpdatesResource;
use Cranleigh\JamfApi\Resources\MdmResource;
use Cranleigh\JamfApi\Resources\MobileDeviceAppsResource;
use Cranleigh\JamfApi\Resources\MobileDeviceGroupsResource;
use Cranleigh\JamfApi\Resources\MobileDeviceManagementCommandsResource;
use Cranleigh\JamfApi\Resources\MobileDevicePrestagesResource;
use Cranleigh\JamfApi\Resources\MobileDevicesResource;
use Cranleigh\JamfApi\Resources\OnboardingResource;
use Cranleigh\JamfApi\Resources\PackagesResource;
use Cranleigh\JamfApi\Resources\PatchManagementResource;
use Cranleigh\JamfApi\Resources\PatchSoftwareTitleConfigurationsResource;
use Cranleigh\JamfApi\Resources\PatchTitlesResource;
use Cranleigh\JamfApi\Resources\RemoteAdministrationResource;
use Cranleigh\JamfApi\Resources\ScriptsResource;
use Cranleigh\JamfApi\Resources\SelfServiceBrandingResource;
use Cranleigh\JamfApi\Resources\SelfServiceResource;
use Cranleigh\JamfApi\Resources\SitesResource;
use Cranleigh\JamfApi\Resources\SsoSettingsResource;
use Cranleigh\JamfApi\Resources\StartupStatusResource;
use Cranleigh\JamfApi\Resources\SupervisionIdentitiesResource;
use Cranleigh\JamfApi\Resources\TeacherAppResource;
use Cranleigh\JamfApi\Resources\UserEnrollmentsResource;
use Cranleigh\JamfApi\Resources\VolumePurchasingLocationsResource;
use Cranleigh\JamfApi\Resources\VolumePurchasingSubscriptionsResource;
use Cranleigh\JamfApi\Resources\WebhooksResource;
use Illuminate\Support\Facades\Facade;

/**
 * Facade for the Jamf Pro API client.
 *
 * All methods proxy to the underlying {@see JamfClient} singleton.
 * The full method list is documented below for IDE autocompletion.
 *
 * @method static AuthResource                              auth()
 * @method static AccountsResource                          accounts()
 * @method static AccountGroupsResource                     accountGroups()
 * @method static ApiRolesResource                          apiRoles()
 * @method static ApiRolePrivilegesResource                 apiRolePrivileges()
 * @method static ApiIntegrationsResource                   apiIntegrations()
 * @method static LdapResource                              ldap()
 * @method static CloudLdapResource                         cloudLdap()
 * @method static SsoSettingsResource                       ssoSettings()
 * @method static ComputerInventoryResource                 computerInventory()
 * @method static ComputerPrestagesResource                 computerPrestages()
 * @method static ComputerGroupsResource                    computerGroups()
 * @method static ComputerManagementResource                computerManagement()
 * @method static MobileDevicesResource                     mobileDevices()
 * @method static MobileDevicePrestagesResource             mobileDevicePrestages()
 * @method static MobileDeviceGroupsResource                mobileDeviceGroups()
 * @method static MobileDeviceAppsResource                  mobileDeviceApps()
 * @method static MobileDeviceManagementCommandsResource    mobileDeviceManagementCommands()
 * @method static AdvancedMobileDeviceSearchesResource      advancedMobileDeviceSearches()
 * @method static EnrollmentResource                        enrollment()
 * @method static EnrollmentCustomizationResource           enrollmentCustomization()
 * @method static DeviceEnrollmentsResource                 deviceEnrollments()
 * @method static UserEnrollmentsResource                   userEnrollments()
 * @method static ApnsClientPushStatusResource              apnsClientPushStatus()
 * @method static ScriptsResource                           scripts()
 * @method static PackagesResource                          packages()
 * @method static CategoriesResource                        categories()
 * @method static DepartmentsResource                       departments()
 * @method static BuildingsResource                         buildings()
 * @method static SitesResource                             sites()
 * @method static IconsResource                             icons()
 * @method static WebhooksResource                          webhooks()
 * @method static PatchManagementResource                   patchManagement()
 * @method static PatchTitlesResource                       patchTitles()
 * @method static PatchSoftwareTitleConfigurationsResource  patchSoftwareTitleConfigurations()
 * @method static InventoryPreloadResource                  inventoryPreload()
 * @method static InventoryInformationResource              inventoryInformation()
 * @method static ExtensionAttributesResource               extensionAttributes()
 * @method static ActivationCodeResource                    activationCode()
 * @method static CheckInResource                           checkIn()
 * @method static SelfServiceResource                       selfService()
 * @method static SelfServiceBrandingResource               selfServiceBranding()
 * @method static OnboardingResource                        onboarding()
 * @method static CacheSettingsResource                     cacheSettings()
 * @method static DashboardResource                         dashboard()
 * @method static JamfProInformationResource                jamfProInformation()
 * @method static StartupStatusResource                     startupStatus()
 * @method static SupervisionIdentitiesResource             supervisionIdentities()
 * @method static LocalAdminPasswordResource                localAdminPassword()
 * @method static VolumePurchasingLocationsResource         volumePurchasingLocations()
 * @method static VolumePurchasingSubscriptionsResource     volumePurchasingSubscriptions()
 * @method static MdmResource                               mdm()
 * @method static ManagedSoftwareUpdatesResource            managedSoftwareUpdates()
 * @method static RemoteAdministrationResource              remoteAdministration()
 * @method static JamfConnectResource                       jamfConnect()
 * @method static TeacherAppResource                        teacherApp()
 * @method static AdvancedUserContentSearchesResource       advancedUserContentSearches()
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
