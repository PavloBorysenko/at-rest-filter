<?php

namespace Supernova\AtRestFilter\Shortcodes;

use Supernova\AtRestFilter\Http\SearchRequest;
use Supernova\AtRestFilter\Helper\Template;

class DeathNoticeCreateListing extends DeathNoticeListing {

    public function __construct() {
        parent::__construct();
    }
    public function init() {
        add_shortcode('at_rest_death_notice_create', array($this, 'render_shortcode'));
    }
    public function render_shortcode($atts = []) {
        $atts = shortcode_atts([
            'post_type' => 'death-notices',
            'per_page' => 6,
            'orderby' => 'date',
            'order' => 'desc',
        ], $atts);
        $per_page_array = [
            6,
            12,
            18,
            24,
            30,
        ];
        $template_helper = $this->templateHelper;
        $per_page = $this->search->get('per-page') ?? $atts['per_page'];
        $orderby = $this->search->get('orderby') ?? $atts['orderby'];
        $order = $this->search->get('order') ?? $atts['order'];
        $user_id = get_current_user_id();
        $search = $this->search;
        $this->initJs();
        ob_start();
        include AT_REST_FILTER_DIR . '/views/listing/death-notice-create.php';
        return ob_get_clean();
    }
    protected function initJs() {
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
        wp_enqueue_script('at-rest-filter-death-notice-create-functions', 
            AT_REST_FILTER_URL . 'js/death-notice-create-functions.js', 
            array('at-rest-death-notice-listing-js'), '1.0.0', true);
       
        wp_localize_script('at-rest-filter-death-notice-create-functions', 'atRestData', [
            'ajaxurl' => admin_url('admin-ajax.php'),
        ]);
    }
}
