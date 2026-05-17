<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Enums;

/**
 * Inventory sections available in the v2 computer inventory API.
 *
 * Pass these as the $section parameter to ComputerInventoryResource::list()
 * or ComputerInventoryResource::find() to control which sections are returned.
 */
enum ComputerInventorySection: string
{
    case General = 'GENERAL';
    case DiskEncryption = 'DISK_ENCRYPTION';
    case Purchasing = 'PURCHASING';
    case Applications = 'APPLICATIONS';
    case Storage = 'STORAGE';
    case UserAndLocation = 'USER_AND_LOCATION';
    case ConfigurationProfiles = 'CONFIGURATION_PROFILES';
    case Printers = 'PRINTERS';
    case Services = 'SERVICES';
    case Hardware = 'HARDWARE';
    case LocalUserAccounts = 'LOCAL_USER_ACCOUNTS';
    case Certificates = 'CERTIFICATES';
    case Attachments = 'ATTACHMENTS';
    case Plugins = 'PLUGINS';
    case PackageReceipts = 'PACKAGE_RECEIPTS';
    case Fonts = 'FONTS';
    case Security = 'SECURITY';
    case OperatingSystem = 'OPERATING_SYSTEM';
    case LicensedSoftware = 'LICENSED_SOFTWARE';
    case Ibeacons = 'IBEACONS';
    case SoftwareUpdates = 'SOFTWARE_UPDATES';
    case ExtensionAttributes = 'EXTENSION_ATTRIBUTES';
    case ContentCaching = 'CONTENT_CACHING';
    case GroupMemberships = 'GROUP_MEMBERSHIPS';

    /**
     * All available sections.
     *
     * @return list<string>
     */
    public static function all(): array
    {
        return array_map(fn (self $s) => $s->value, self::cases());
    }
}
