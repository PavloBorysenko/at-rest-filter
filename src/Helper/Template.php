<?php

namespace Supernova\AtRestFilter\Helper;

class Template {


    public static function drawPagination( array $per_page_array): void {
        include_once AT_REST_FILTER_DIR . '/views/listing/pagination.php';
    }

    public static function drawEmptyState(): void {
        include_once AT_REST_FILTER_DIR . '/views/listing/empty-state.php';
    }

    public static function connectTippy(): void {
        wp_enqueue_script('popper-js', AT_REST_FILTER_URL . '/js/tippy/popper.min.js', [], '2.11.8', true);
        wp_enqueue_script('tippy-js', AT_REST_FILTER_URL . '/js/tippy/tippy.min.js', ['popper-js'], '6.3.7', true);
        wp_enqueue_style('tippy-css', AT_REST_FILTER_URL . '/css/tippy/tippy.css', [], '6.3.7');
    }
    public static function connectMainCSS(): void {
        wp_enqueue_style('at-rest-notice-listing-css', AT_REST_FILTER_URL . 'css/notice-listing.css', array(), '1.0.0');
    }
    public static function connectListingJsSettings(): void {
        wp_enqueue_script('at-rest-template-renderer-js', AT_REST_FILTER_URL . 'js/template-renderer.js', array(), '1.0.3', true);
        self::connectUrlManager();
        wp_enqueue_script('at-rest-pagination-manager-js', AT_REST_FILTER_URL . 'js/pagination-manager.js', array('at-rest-url-manager-js'), '1.0.0', true);
        wp_enqueue_script('at-rest-per-page-manager-js', AT_REST_FILTER_URL . 'js/per-page-manager.js', array('at-rest-url-manager-js'), '1.0.2', true);
        wp_enqueue_script('at-rest-sort-manager-js', AT_REST_FILTER_URL . 'js/sort-manager.js', array('at-rest-url-manager-js'), '1.0.2', true);
        wp_enqueue_script('at-rest-post-listing-js', AT_REST_FILTER_URL . 'js/post-listing.js', array(
            'at-rest-template-renderer-js',
            'at-rest-url-manager-js',
            'at-rest-pagination-manager-js',
            'at-rest-per-page-manager-js',
            'at-rest-sort-manager-js'
        ), '2.0.1', true);        
    }
    public static function connectUrlManager(): void {
        wp_enqueue_script('at-rest-url-manager-js', AT_REST_FILTER_URL . 'js/url-manager.js', array(), '1.0.1', true);
    }

    public static function connectFlatpickr(): void {
        wp_enqueue_style('flatpickr-css', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css', array(), '4.6.13');
        wp_enqueue_script('flatpickr-js', 'https://cdn.jsdelivr.net/npm/flatpickr', array(), '4.6.13', true);
    }
    public static function connectChoices(): void {
        wp_enqueue_style('choices-css', 'https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css', array(), '1.0.1');
        wp_enqueue_script('choices-js', 'https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js', array(), '1.0.1', true);
    }
}