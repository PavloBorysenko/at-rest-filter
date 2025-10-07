<?php

namespace Supernova\AtRestFilter\Shortcodes;

use Supernova\AtRestFilter\Http\SearchRequest;

class DeathNoticeListining {

    private $search;
    public function __construct() {
        $this->search = new SearchRequest([
            'per-page' => 'int',
            'page' => 'int',
            'orderby' => 'string',
            'order' => 'string',
        ]);
        $this->init();
    }
    public function init() {
        add_shortcode('at_rest_death_notice_listing', array($this, 'render_shortcode'));
    }
    public function render_shortcode($atts = []) {
        $atts = shortcode_atts([
            'per_page' => 6,
            'orderby' => 'date',
            'order' => 'desc',
        ], $atts);
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
        include AT_REST_FILTER_DIR . '/views/listing/death-notice.php';
        return ob_get_clean();
    }

    private function initJs() {
        // Enqueue Choices.js library (assuming it's already registered in WordPress)
        // If Choices.js is not registered, you need to register it first
       // wp_enqueue_style('choices-css', 'https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/styles/choices.min.css', array(), '10.2.0');
       // wp_enqueue_script('choices-js', 'https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/scripts/choices.min.js', array(), '10.2.0', true);
        
        // Enqueue pagination CSS
        wp_enqueue_style('at-rest-pagination-css', AT_REST_FILTER_URL . 'css/pagination.css', array(), '1.0.1');
        
        // Enqueue our custom script with Choices.js as dependency
        wp_enqueue_script('at-rest-death-notice-listing-js', AT_REST_FILTER_URL . 'js/death-notice-listing.js', array(), '1.0.6', true);
    }
}


