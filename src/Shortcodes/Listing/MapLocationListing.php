<?php

namespace Supernova\AtRestFilter\Shortcodes\Listing;

use Supernova\AtRestFilter\Http\SearchRequest;
use Supernova\AtRestFilter\Helper\Template;

class MapLocationListing {
    private SearchRequest $search;
    private Template $templateHelper;
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

        $this->templateHelper->connectMainCSS();
        $this->templateHelper->connectListingJsSettings();
        $this->templateHelper->connectTippy();

        wp_enqueue_script('at-rest-filter-map-location-functions', AT_REST_FILTER_URL . 'js/functions/map-location-functions.js', array('at-rest-post-listing-js'), '1.0.1', true);
        wp_localize_script('at-rest-filter-map-location-functions', 'atRestData', [
            'ajaxurl' => admin_url('admin-ajax.php'),
        ]);
    }
}