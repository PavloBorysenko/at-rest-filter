<?php

namespace Supernova\AtRestFilter\Shortcodes;

use Supernova\AtRestFilter\Http\SearchRequest;
use Supernova\AtRestFilter\Helper\Template;
class DeathNoticeListing {

    protected $search;
    protected $templateHelper;
    public function __construct() {
        $this->templateHelper = new Template();
        $this->search = new SearchRequest([
            'per-page' => 'int',
            'pg' => 'int',
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
        include AT_REST_FILTER_DIR . '/views/listing/death-notice.php';
        return ob_get_clean();
    }

    protected function initJs() {
        // Enqueue Choices.js library (assuming it's already registered in WordPress)
        // If Choices.js is not registered, you need to register it first
       // wp_enqueue_style('choices-css', 'https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/styles/choices.min.css', array(), '10.2.0');
       // wp_enqueue_script('choices-js', 'https://cdn.jsdelivr.net/npm/choices.js@10.2.0/public/assets/scripts/choices.min.js', array(), '10.2.0', true);
        
        // Enqueue notice listing CSS
        wp_enqueue_style('at-rest-notice-listing-css', AT_REST_FILTER_URL . 'css/notice-listing.css', array(), '1.0.0');
        
        

        // Enqueue our custom scripts with proper dependencies
        wp_enqueue_script('at-rest-template-renderer-js', AT_REST_FILTER_URL . 'js/template-renderer.js', array(), '1.0.3', true);
        wp_enqueue_script('at-rest-url-manager-js', AT_REST_FILTER_URL . 'js/url-manager.js', array(), '1.0.1', true);
        wp_enqueue_script('at-rest-pagination-manager-js', AT_REST_FILTER_URL . 'js/pagination-manager.js', array('at-rest-url-manager-js'), '1.0.0', true);
        wp_enqueue_script('at-rest-per-page-manager-js', AT_REST_FILTER_URL . 'js/per-page-manager.js', array('at-rest-url-manager-js'), '1.0.2', true);
        wp_enqueue_script('at-rest-sort-manager-js', AT_REST_FILTER_URL . 'js/sort-manager.js', array('at-rest-url-manager-js'), '1.0.2', true);
        wp_enqueue_script('at-rest-death-notice-listing-js', AT_REST_FILTER_URL . 'js/post-listing.js', array(
            'at-rest-template-renderer-js',
            'at-rest-url-manager-js',
            'at-rest-pagination-manager-js',
            'at-rest-per-page-manager-js',
            'at-rest-sort-manager-js'
        ), '2.0.1', true);
        $this->templateHelper->connectTippy();

       
    }
}


