<?php

declare(strict_types=1);

use Cranleigh\JamfApi\Data\Script\Script;
use Cranleigh\JamfApi\Facades\Jamf;
use Cranleigh\JamfApi\Pagination\Page;
use Illuminate\Support\Facades\Http;

beforeEach(function (): void {
    Http::preventStrayRequests();

    Http::fake([
        'jamf.example.com/api/v1/auth/token' => Http::response([
            'token'   => 'bearer-token',
            'expires' => now()->addMinutes(30)->toIso8601String(),
        ]),
    ]);
});

it('lists scripts and returns typed page', function (): void {
    Http::fake([
        'jamf.example.com/api/v1/scripts*' => Http::response([
            'totalCount' => 1,
            'results'    => [
                ['id' => '10', 'name' => 'Deploy Homebrew', 'scriptContents' => '#!/bin/bash'],
            ],
        ]),
    ]);

    $page = Jamf::scripts()->list();

    expect($page)->toBeInstanceOf(Page::class)
        ->and($page->results[0])->toBeInstanceOf(Script::class)
        ->and($page->results[0]->name)->toBe('Deploy Homebrew');
});

it('finds a script by id', function (): void {
    Http::fake([
        'jamf.example.com/api/v1/scripts/10' => Http::response([
            'id' => '10', 'name' => 'Deploy Homebrew', 'scriptContents' => '#!/bin/bash',
        ]),
    ]);

    $script = Jamf::scripts()->find('10');

    expect($script)->toBeInstanceOf(Script::class)
        ->and($script->id)->toBe('10');
});

it('creates a script', function (): void {
    Http::fake([
        'jamf.example.com/api/v1/scripts' => Http::response([
            'id' => '99', 'name' => 'New Script', 'scriptContents' => '#!/bin/zsh',
        ], 201),
    ]);

    $script = Jamf::scripts()->create(['name' => 'New Script', 'scriptContents' => '#!/bin/zsh']);

    expect($script->id)->toBe('99')
        ->and($script->name)->toBe('New Script');
});

it('deletes a script', function (): void {
    Http::fake([
        'jamf.example.com/api/v1/scripts/10' => Http::response(null, 204),
    ]);

    expect(fn () => Jamf::scripts()->delete('10'))->not->toThrow(\Exception::class);
});
