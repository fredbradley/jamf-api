<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Enums;

/**
 * MDM command types that can be sent to managed devices.
 *
 * Use these with MdmResource::send() or MobileDeviceManagementCommandsResource::send().
 *
 * The SETTINGS command type is special — it accepts additional parameters in the
 * request body (bluetooth, dataRoaming, voiceRoaming, personalHotspot). Use the
 * convenience methods on MobileDeviceManagementCommandsResource instead of building
 * the payload by hand.
 */
enum MdmCommandType: string
{
    // Device lifecycle
    case DeviceLock = 'DEVICE_LOCK';
    case EraseDevice = 'ERASE_DEVICE';
    case RestartDevice = 'RESTART_DEVICE';
    case ShutDownDevice = 'SHUT_DOWN_DEVICE';
    case LogOutUser = 'LOG_OUT_USER';
    case UnlockUserAccount = 'UNLOCK_USER_ACCOUNT';

    // Profiles and apps
    case RemoveProfile = 'REMOVE_PROFILE';
    case InstallProfile = 'INSTALL_PROFILE';
    case RemoveApplication = 'REMOVE_APPLICATION';
    case ValidateApplications = 'VALIDATE_APPLICATIONS';
    case ManagedApplicationList = 'MANAGED_APPLICATION_LIST';
    case ManagedMediaList = 'MANAGED_MEDIA_LIST';
    case InstalledApplicationList = 'INSTALLED_APPLICATION_LIST';
    case ProfileList = 'PROFILE_LIST';
    case ProvisioningProfileList = 'PROVISIONING_PROFILE_LIST';
    case ApplyRedemptionCode = 'APPLY_REDEMPTION_CODE';

    // Enrollment
    case UnmanageDevice = 'UNMANAGE_DEVICE';
    case DeleteUser = 'DELETE_USER';
    case DeclarativeManagement = 'DECLARATIVE_MANAGEMENT';

    // Security
    case ClearPasscode = 'CLEAR_PASSCODE';
    case ClearRestrictionsPassword = 'CLEAR_RESTRICTIONS_PASSWORD';
    case ClearActivationLock = 'CLEAR_ACTIVATION_LOCK';
    case SecurityInfo = 'SECURITY_INFO';
    case SetAutoAdminPassword = 'SET_AUTO_ADMIN_PASSWORD';
    case SetRecoveryLock = 'SET_RECOVERY_LOCK';
    case VerifyRecoveryLock = 'VERIFY_RECOVERY_LOCK';
    case CertificateList = 'CERTIFICATE_LIST';

    // Software updates
    case ScheduleOsUpdate = 'SCHEDULE_OS_UPDATE';
    case InstallOsUpdate = 'INSTALL_OS_UPDATE_DEFAULT';
    case RefreshCellularPlans = 'REFRESH_CELLULAR_PLANS';

    // Inventory
    case UpdateInventory = 'UPDATE_INVENTORY';
    case DeviceInformation = 'DEVICE_INFORMATION';
    case DeviceLocation = 'DEVICE_LOCATION';

    // Lost mode
    case EnableLostMode = 'ENABLE_LOST_MODE';
    case DisableLostMode = 'DISABLE_LOST_MODE';
    case PlayLostModeSound = 'PLAY_LOST_MODE_SOUND';
    case UpdateLocation = 'UPDATE_LOCATION';

    // Screen sharing (Mac)
    case EnableRemoteDesktop = 'ENABLE_REMOTE_DESKTOP';
    case DisableRemoteDesktop = 'DISABLE_REMOTE_DESKTOP';
    case RequestMirroring = 'REQUEST_MIRRORING';
    case StopMirroring = 'STOP_MIRRORING';

    // Device settings — use MobileDeviceManagementCommandsResource convenience methods
    case Settings = 'SETTINGS';

    // Connectivity
    case BlankPush = 'BLANK_PUSH';
}
