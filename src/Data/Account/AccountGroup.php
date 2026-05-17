<?php

declare(strict_types=1);

namespace FredBradley\JamfApi\Data\Account;

/**
 * A Jamf Pro account group.
 */
readonly class AccountGroup
{
    public function __construct(
        /** Unique numeric identifier. */
        public string $id,
        /** Group name. */
        public string $name,
        /** The access level for members of this group. */
        public ?string $accessLevel,
        /** The privilege set assigned to this group. */
        public ?string $privilegeSet,
        /** Associated LDAP server ID, if any. */
        public ?string $ldapServerId,
        /** Number of user accounts in this group. */
        public int $memberCount,
    ) {}

    /**
     * @param  array<string,mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) ($data['id'] ?? ''),
            name: $data['name'] ?? '',
            accessLevel: $data['accessLevel'] ?? null,
            privilegeSet: $data['privilegeSet'] ?? null,
            ldapServerId: isset($data['ldapServer']['id']) ? (string) $data['ldapServer']['id'] : null,
            memberCount: (int) ($data['memberCount'] ?? 0),
        );
    }
}
