<?php

declare(strict_types=1);

namespace HookSniff;

/**
 * Pagination Helper for HookSniff PHP SDK.
 *
 * Provides automatic cursor-based pagination for list() methods.
 *
 * Usage:
 *   // Auto-paginate through all items
 *   foreach (Paginator::paginate(fn($opts) => $hs->message->list(null, $opts)) as $msg) {
 *       echo $msg->id;
 *   }
 *
 *   // With options
 *   foreach (Paginator::paginate(fn($opts) => $hs->message->list(null, $opts), limit: 100) as $msg) {
 *       echo $msg->id;
 *   }
 */
class Paginator
{
    /**
     * Auto-paginate through all items using a generator.
     *
     * @param callable $fetchPage Function that fetches a page given options
     * @param int|null $limit Page size
     * @param string|null $iterator Initial cursor
     * @return \Generator Individual items
     */
    public static function paginate(
        callable $fetchPage,
        ?int $limit = null,
        ?string $iterator = null,
    ): \Generator {
        while (true) {
            $options = new \stdClass();
            if ($limit !== null) {
                $options->limit = $limit;
            }
            if ($iterator !== null) {
                $options->iterator = $iterator;
            }

            $page = $fetchPage($options);

            foreach ($page->data as $item) {
                yield $item;
            }

            if ($page->done || empty($page->iterator)) {
                break;
            }

            $iterator = $page->iterator;
        }
    }

    /**
     * Collect all items into an array.
     *
     * @param callable $fetchPage Function that fetches a page
     * @param int|null $limit Page size
     * @return array All items
     */
    public static function all(
        callable $fetchPage,
        ?int $limit = null,
    ): array {
        $items = [];
        foreach (self::paginate($fetchPage, $limit) as $item) {
            $items[] = $item;
        }
        return $items;
    }

    /**
     * Iterate through pages (not individual items).
     *
     * @param callable $fetchPage Function that fetches a page
     * @param int|null $limit Page size
     * @return \Generator Full page responses
     */
    public static function pages(
        callable $fetchPage,
        ?int $limit = null,
    ): \Generator {
        $iterator = null;

        while (true) {
            $options = new \stdClass();
            if ($limit !== null) {
                $options->limit = $limit;
            }
            if ($iterator !== null) {
                $options->iterator = $iterator;
            }

            $page = $fetchPage($options);

            yield $page;

            if ($page->done || empty($page->iterator)) {
                break;
            }

            $iterator = $page->iterator;
        }
    }
}
