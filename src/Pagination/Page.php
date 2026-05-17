<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Pagination;

/**
 * A single page of results from a paginated Jamf Pro API endpoint.
 *
 * @template T The DTO type contained in this page.
 */
readonly class Page
{
    /**
     * @param list<T> $results     The items on this page.
     * @param int     $totalCount  Total number of items across all pages.
     * @param int     $pageNumber  Zero-based page index.
     * @param int     $pageSize    Maximum items per page requested.
     */
    public function __construct(
        public array $results,
        public int $totalCount,
        public int $pageNumber,
        public int $pageSize,
    ) {}

    /**
     * Whether more pages exist after this one.
     */
    public function hasMorePages(): bool
    {
        return ($this->pageNumber + 1) * $this->pageSize < $this->totalCount;
    }

    /**
     * The zero-based index of the next page, or null if this is the last page.
     */
    public function nextPage(): ?int
    {
        return $this->hasMorePages() ? $this->pageNumber + 1 : null;
    }

    /**
     * Total number of pages.
     */
    public function totalPages(): int
    {
        if ($this->pageSize === 0) {
            return 0;
        }

        return (int) ceil($this->totalCount / $this->pageSize);
    }

    /**
     * Whether the current page is the first page.
     */
    public function isFirstPage(): bool
    {
        return $this->pageNumber === 0;
    }

    /**
     * Whether the current page is the last page.
     */
    public function isLastPage(): bool
    {
        return ! $this->hasMorePages();
    }

    /**
     * The number of results on this page.
     */
    public function count(): int
    {
        return count($this->results);
    }
}
