<?php

declare(strict_types=1);

use Cranleigh\JamfApi\Data\Computer\ComputerSummary;
use Cranleigh\JamfApi\Facades\Jamf;
use Cranleigh\JamfApi\Pagination\Page;
use Illuminate\Support\Facades\Http;

beforeEach(function (): void {
    Http::preventStrayRequests();
});

it('lists computers and returns a typed page', function (): void {
    Http::fake([
        'jamf.example.com/api/v1/auth/token' => Http::response([
            'token' => 'test-bearer-token',
            'expires' => now()->addMinutes(30)->toIso8601String(),
        ]),
        'jamf.example.com/api/v2/computers-inventory*' => Http::response([
            'totalCount' => 2,
            'results' => [
                ['id' => '1', 'udid' => 'UDID-1', 'name' => 'MacBook 1', 'managed' => true, 'supervised' => true],
                ['id' => '2', 'udid' => 'UDID-2', 'name' => 'MacBook 2', 'managed' => false, 'supervised' => false],
            ],
        ]),
    ]);

    $page = Jamf::computerInventory()->list();

    expect($page)->toBeInstanceOf(Page::class)
        ->and($page->totalCount)->toBe(2)
        ->and($page->results)->toHaveCount(2)
        ->and($page->results[0])->toBeInstanceOf(ComputerSummary::class)
        ->and($page->results[0]->name)->toBe('MacBook 1')
        ->and($page->results[0]->managed)->toBeTrue();
});

it('sends filter and sort parameters to the API', function (): void {
    Http::fake([
        'jamf.example.com/api/v1/auth/token' => Http::response([
            'token' => 'test-bearer-token',
            'expires' => now()->addMinutes(30)->toIso8601String(),
        ]),
        'jamf.example.com/api/v2/computers-inventory*' => Http::response([
            'totalCount' => 0,
            'results' => [],
        ]),
    ]);

    Jamf::computerInventory()->list(
        page: 1,
        pageSize: 50,
        sort: ['general.name:asc'],
        filter: 'general.name=="MacBook*"',
    );

    Http::assertSent(function ($request): bool {
        $url = $request->url();

        return str_contains($url, 'page=1')
            && str_contains($url, 'page-size=50')
            && str_contains($url, 'filter=');
    });
});
