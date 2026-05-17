<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Enums;

/**
 * Sort direction used in API list/search queries.
 */
enum SortOrder: string
{
    case Asc = 'asc';
    case Desc = 'desc';

    /**
     * Build a sort parameter string for a given field.
     *
     * Example: SortOrder::Asc->for('name') returns 'name:asc'
     */
    public function for(string $field): string
    {
        return "{$field}:{$this->value}";
    }
}
