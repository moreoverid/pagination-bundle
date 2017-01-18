<?php

namespace Moreoverid\PaginationBundle\Model;

class Pagination
{
    static function paginate($current_page = 1, $total_count = 0, $count_per_page = 24, $pages_on_page = 10)
    {
        // $current_page - number of current page
        // $total_count - number of items in entity (total elements)
        // $count_per_page - how many items to show per page (elements per page)
        // $pages_on_page - how many pages in pagination show on the current page

        $pages_array = array();
        $total_pages = ceil($total_count / $count_per_page);
        $chunks_count = ceil($total_pages / $pages_on_page);
        $pages_from_left = 4;
        $pages_from_right = 5;

        if ($chunks_count < 1 or $chunks_count > $total_pages) {
            $chunks_count = 1;
        }

        // check current page value
        if (!is_numeric($current_page) or $current_page < 1) {
            $current_page = 1;
        } else {
            $current_page = floor($current_page);
        }

        // fix unknown page shifting values
        if ($total_count <= $count_per_page) {
            $current_page = 1;
        }

        if (($current_page * $count_per_page) > $total_count) {
            $current_page = $total_pages;
        }

        $offset = 0;

        // calculating page shifting
        if ($current_page > 1) {
            $offset = $count_per_page * ($current_page - 1);
        } else {
            $current_page = 1;
        }

        // calculate current_chunk
        for ($i = 1; $i <= $total_pages; $i++) {
            $pages_array[$i + 1] = $i;
        }

        $pages_chunks = array_chunk($pages_array, $pages_on_page, true);
        $current_chunk = self::getPagesArray($pages_chunks, $current_page);
        $pages_chunks = !empty($pages_chunks[0]) ? $pages_chunks : array(1);

        // generate current page array with offset
        $current_pages_array = array();

        if (($current_page + $pages_from_right) < $pages_on_page) {
            $current_pages_array = $pages_chunks[$current_chunk];

        } elseif (($current_page + $pages_from_right) > $total_pages and $total_pages > $pages_on_page) {
            for ($j = $total_pages - $current_page; $j <= $total_pages; $j++) {
                if ($j > $current_page - $pages_on_page) {
                    $current_pages_array[] = $j;
                }
            }

        } elseif (!(($current_page + $pages_from_right) > $total_pages)) {
            for ($j = 1; $j <= $current_page + $pages_from_right; $j++) {
                if ($j >= $current_page - $pages_from_left and $j > 0) {
                    $current_pages_array[] = $j;
                }
            }

        } else {
            $current_pages_array = $pages_chunks[$current_chunk];
        }

        return array(
            'offset' => $offset,
            'current_chunk' => $current_chunk,
            'pages_chunks' => $pages_chunks,
            'count_per_page' => $count_per_page,
            'current_pages_array' => $current_pages_array,
            'total_pages' => !empty($total_pages) ? $total_pages : 0,
            'total_count' => $total_count,
            'current_page' => $current_page
        );
    }

    static function getPagesArray($pagesList, $needPage)
    {
        foreach ($pagesList AS $chunk => $pages) {
            if (in_array($needPage, $pages)) {
                return $chunk;
            }
        }

        return 0;
    }
}
