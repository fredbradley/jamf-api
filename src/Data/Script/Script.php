<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Data\Script;

/**
 * A Jamf Pro script.
 */
readonly class Script
{
    public function __construct(
        /** Unique identifier. */
        public string $id,
        /** Script display name. */
        public string $name,
        /** Optional category name. */
        public ?string $categoryName,
        /** Category ID. */
        public ?string $categoryId,
        /** Human-readable description. */
        public ?string $info,
        /** Notes visible to administrators. */
        public ?string $notes,
        /** Execution priority: 'BEFORE', 'AFTER', or 'AT_REBOOT'. */
        public ?string $priority,
        /** Parameter 4 label. */
        public ?string $parameter4,
        /** Parameter 5 label. */
        public ?string $parameter5,
        /** Parameter 6 label. */
        public ?string $parameter6,
        /** Parameter 7 label. */
        public ?string $parameter7,
        /** Parameter 8 label. */
        public ?string $parameter8,
        /** Parameter 9 label. */
        public ?string $parameter9,
        /** Parameter 10 label. */
        public ?string $parameter10,
        /** Parameter 11 label. */
        public ?string $parameter11,
        /** OS requirement string, e.g. '>=10.15'. */
        public ?string $osRequirements,
        /** Script interpreter: 'AFTER', 'BASH', '/bin/zsh', etc. */
        public ?string $scriptContents,
        /** ISO 8601 timestamp when the script was last modified. */
        public ?string $modificationDate,
    ) {}

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id:               (string) ($data['id'] ?? ''),
            name:             $data['name'] ?? '',
            categoryName:     $data['categoryName'] ?? null,
            categoryId:       isset($data['categoryId']) ? (string) $data['categoryId'] : null,
            info:             $data['info'] ?? null,
            notes:            $data['notes'] ?? null,
            priority:         $data['priority'] ?? null,
            parameter4:       $data['parameter4'] ?? null,
            parameter5:       $data['parameter5'] ?? null,
            parameter6:       $data['parameter6'] ?? null,
            parameter7:       $data['parameter7'] ?? null,
            parameter8:       $data['parameter8'] ?? null,
            parameter9:       $data['parameter9'] ?? null,
            parameter10:      $data['parameter10'] ?? null,
            parameter11:      $data['parameter11'] ?? null,
            osRequirements:   $data['osRequirements'] ?? null,
            scriptContents:   $data['scriptContents'] ?? null,
            modificationDate: $data['modificationDate'] ?? null,
        );
    }
}
