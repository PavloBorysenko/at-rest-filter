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

    public static function connectTippy(): void {
        wp_enqueue_script('popper-js', AT_REST_FILTER_URL . '/js/tippy/popper.min.js', [], '2.11.8', true);
        wp_enqueue_script('tippy-js', AT_REST_FILTER_URL . '/js/tippy/tippy.min.js', ['popper-js'], '6.3.7', true);
        wp_enqueue_style('tippy-css', AT_REST_FILTER_URL . '/css/tippy/tippy.css', [], '6.3.7');
    }
}