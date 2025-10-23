<?php

namespace Supernova\AtRestFilter\Helper;

class Template {


    public static function drawPagination( array $per_page_array): void {
        include_once AT_REST_FILTER_DIR . '/views/listing/pagination.php';
    }

    public static function drawEmptyState(): void {
        include_once AT_REST_FILTER_DIR . '/views/listing/empty-state.php';
    }

    public static function drawSpinner(): void {
        include_once AT_REST_FILTER_DIR . '/views/listing/spinner.php';
    }
}