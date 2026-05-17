<?php

declare(strict_types=1);

use FredBradley\JamfApi\Enums\SortOrder;

it('generates sort parameter strings', function (): void {
    expect(SortOrder::Asc->for('name'))->toBe('name:asc')
        ->and(SortOrder::Desc->for('general.name'))->toBe('general.name:desc');
});

it('has the correct raw values', function (): void {
    expect(SortOrder::Asc->value)->toBe('asc')
        ->and(SortOrder::Desc->value)->toBe('desc');
});
