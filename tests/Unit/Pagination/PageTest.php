<?php

declare(strict_types=1);

use Cranleigh\JamfApi\Pagination\Page;

it('reports has more pages correctly', function (): void {
    $page = new Page(results: range(1, 10), totalCount: 25, pageNumber: 0, pageSize: 10);

    expect($page->hasMorePages())->toBeTrue()
        ->and($page->isFirstPage())->toBeTrue()
        ->and($page->isLastPage())->toBeFalse()
        ->and($page->totalPages())->toBe(3)
        ->and($page->nextPage())->toBe(1)
        ->and($page->count())->toBe(10);
});

it('reports no more pages on the last page', function (): void {
    $page = new Page(results: range(1, 5), totalCount: 25, pageNumber: 2, pageSize: 10);

    expect($page->hasMorePages())->toBeFalse()
        ->and($page->isLastPage())->toBeTrue()
        ->and($page->nextPage())->toBeNull();
});

it('handles zero results', function (): void {
    $page = new Page(results: [], totalCount: 0, pageNumber: 0, pageSize: 100);

    expect($page->hasMorePages())->toBeFalse()
        ->and($page->totalPages())->toBe(0)
        ->and($page->count())->toBe(0);
});
