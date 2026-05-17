<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Data\Account;

/**
 * A Jamf Pro user account.
 */
readonly class Account
{
    public function __construct(
        /** Unique numeric identifier. */
        public string $id,
        /** Login username. */
        public string $username,
        /** Full display name. */
        public ?string $fullName,
        /** Email address. */
        public ?string $email,
        /** User's phone number. */
        public ?string $phoneNumber,
        /** Position / job title. */
        public ?string $position,
        /** Whether the account is enabled. */
        public bool $enabled,
        /** Whether full-access privileges are granted. */
        public bool $forcePasswordChange,
        /** The access level: 'Full Access', 'Site Access', or 'Group Access'. */
        public ?string $accessLevel,
        /** The privilege set: 'Administrator', 'Auditor', 'Enrollment Only', or 'Custom'. */
        public ?string $privilegeSet,
        /** LDAP server ID if the account is LDAP-sourced. */
        public ?string $ldapServerId,
    ) {}

    /**
     * @param  array<string,mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) ($data['id'] ?? ''),
            username: $data['username'] ?? '',
            fullName: $data['fullName'] ?? null,
            email: $data['email'] ?? null,
            phoneNumber: $data['phoneNumber'] ?? null,
            position: $data['position'] ?? null,
            enabled: (bool) ($data['enabled'] ?? true),
            forcePasswordChange: (bool) ($data['forcePasswordChange'] ?? false),
            accessLevel: $data['accessLevel'] ?? null,
            privilegeSet: $data['privilegeSet'] ?? null,
            ldapServerId: isset($data['ldapServer']['id']) ? (string) $data['ldapServer']['id'] : null,
        );
    }
}
