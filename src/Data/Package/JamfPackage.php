<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Data\Package;

/**
 * A Jamf Pro package record.
 */
readonly class JamfPackage
{
    public function __construct(
        /** Unique identifier. */
        public string $id,
        /** Package display name. */
        public string $packageName,
        /** Filename of the package file. */
        public string $fileName,
        /** Category name. */
        public ?string $categoryId,
        /** Human-readable description. */
        public ?string $info,
        /** Notes visible to administrators. */
        public ?string $notes,
        /** Priority used when installing multiple packages. */
        public ?int $priority,
        /** Whether to reboot after install. */
        public bool $rebootRequired,
        /** Whether to fill existing user home directories. */
        public bool $fillUserTemplate,
        /** Whether to fill all existing users' home directories. */
        public bool $fillExistingUsers,
        /** Whether the package allows uninstalling. */
        public bool $allowUninstalled,
        /** Index of the install step for ordered install operations. */
        public ?string $osRequirements,
        /** The required minimum CPU type. */
        public ?string $requiredProcessor,
        /** Whether to switch the boot volume after install. */
        public bool $switchWithPackage,
        /** Whether to install if already installed. */
        public bool $installIfReportedAvailable,
        /** MD5 checksum. */
        public ?string $md5,
        /** SHA-256 checksum. */
        public ?string $sha256,
        /** SHA3-512 checksum. */
        public ?string $hashValue,
        /** The hash type used. */
        public ?string $hashType,
        /** File size in bytes. */
        public ?int $packageSize,
    ) {}

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id:                         (string) ($data['id'] ?? ''),
            packageName:                $data['packageName'] ?? '',
            fileName:                   $data['fileName'] ?? '',
            categoryId:                 isset($data['categoryId']) ? (string) $data['categoryId'] : null,
            info:                       $data['info'] ?? null,
            notes:                      $data['notes'] ?? null,
            priority:                   isset($data['priority']) ? (int) $data['priority'] : null,
            rebootRequired:             (bool) ($data['rebootRequired'] ?? false),
            fillUserTemplate:           (bool) ($data['fillUserTemplate'] ?? false),
            fillExistingUsers:          (bool) ($data['fillExistingUsers'] ?? false),
            allowUninstalled:           (bool) ($data['allowUninstalled'] ?? false),
            osRequirements:             $data['osRequirements'] ?? null,
            requiredProcessor:          $data['requiredProcessor'] ?? null,
            switchWithPackage:          (bool) ($data['switchWithPackage'] ?? false),
            installIfReportedAvailable: (bool) ($data['installIfReportedAvailable'] ?? false),
            md5:                        $data['md5'] ?? null,
            sha256:                     $data['sha256'] ?? null,
            hashValue:                  $data['hashValue'] ?? null,
            hashType:                   $data['hashType'] ?? null,
            packageSize:                isset($data['packageSize']) ? (int) $data['packageSize'] : null,
        );
    }
}
