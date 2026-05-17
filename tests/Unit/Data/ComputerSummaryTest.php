<?php

declare(strict_types=1);

use FredBradley\JamfApi\Data\Computer\ComputerSummary;

it('creates a computer summary from a flat array', function (): void {
    $data = [
        'id' => '42',
        'udid' => 'ABCD-1234',
        'name' => 'MacBook Pro',
        'managed' => true,
        'supervised' => false,
    ];

    $summary = ComputerSummary::fromArray($data);

    expect($summary->id)->toBe('42')
        ->and($summary->udid)->toBe('ABCD-1234')
        ->and($summary->name)->toBe('MacBook Pro')
        ->and($summary->managed)->toBeTrue()
        ->and($summary->supervised)->toBeFalse()
        ->and($summary->serialNumber)->toBeNull();
});

it('handles missing optional fields gracefully', function (): void {
    $summary = ComputerSummary::fromArray(['id' => '1', 'udid' => 'test-udid']);

    expect($summary->name)->toBeNull()
        ->and($summary->email)->toBeNull()
        ->and($summary->department)->toBeNull();
});
