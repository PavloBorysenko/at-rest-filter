<?php

namespace Supernova\AtRestFilter\Shortcodes;

use Supernova\AtRestFilter\Http\SearchRequest;
use Supernova\AtRestFilter\Helper\Template;

class MapLocationListing {
    private $search;
    private $templateHelper;
    public function __construct() {
        $this->templateHelper = new Template();
        $this->search = new SearchRequest([
            'per-page' => 'int',
            'page' => 'int',
            'orderby' => 'string',
            'order' => 'string',
        ]);
        $this->init();
    }

    public function init() {
        add_shortcode('at_rest_map_location_listing', [$this, 'render']); 
    }

    public function render($atts) {

        if (!is_user_logged_in()) return 'Please log in.';

        $atts = shortcode_atts([
            'per_page' => 6,
            'orderby' => 'date',
            'order' => 'desc',
        ], $atts);
        
        $user_id = get_current_user_id();
        $template_helper = $this->templateHelper;
        $per_page = $this->search->get('per-page') ?? $atts['per_page'];
        $orderby = $this->search->get('orderby') ?? $atts['orderby'];
        $order = $this->search->get('order') ?? $atts['order'];
        $search = $this->search;
        $per_page_array = [
            6,
            12,
            18,
            24,
            30,
        ];
        $this->initJs();
        ob_start();
        include AT_REST_FILTER_DIR . '/views/listing/map-location.php';
        return ob_get_clean();
    }

    private function initJs() {

        wp_enqueue_style('at-rest-notice-listing-css', AT_REST_FILTER_URL . 'css/notice-listing.css', array(), '1.0.0');

        wp_enqueue_script('at-rest-template-renderer-js', AT_REST_FILTER_URL . 'js/template-renderer.js', array(), '1.0.3', true);
        wp_enqueue_script('at-rest-url-manager-js', AT_REST_FILTER_URL . 'js/url-manager.js', array(), '1.0.1', true);
        wp_enqueue_script('at-rest-pagination-manager-js', AT_REST_FILTER_URL . 'js/pagination-manager.js', array('at-rest-url-manager-js'), '1.0.0', true);
        wp_enqueue_script('at-rest-per-page-manager-js', AT_REST_FILTER_URL . 'js/per-page-manager.js', array('at-rest-url-manager-js'), '1.0.2', true);
        wp_enqueue_script('at-rest-sort-manager-js', AT_REST_FILTER_URL . 'js/sort-manager.js', array('at-rest-url-manager-js'), '1.0.2', true);

        wp_enqueue_script('at-rest-filter-map-location-listing', AT_REST_FILTER_URL . 'js/post-listing.js', [
            'at-rest-template-renderer-js',
            'at-rest-url-manager-js',
            'at-rest-pagination-manager-js',
            'at-rest-per-page-manager-js',
            'at-rest-sort-manager-js'
        ], '1.0.0', true);

        wp_enqueue_script('at-rest-filter-map-location-functions', AT_REST_FILTER_URL . 'js/map-location-functions.js', array('at-rest-filter-map-location-listing'), '1.0.0', true);
    }
}