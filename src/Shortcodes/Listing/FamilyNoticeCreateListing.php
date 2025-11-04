<?php

namespace Supernova\AtRestFilter\Shortcodes\Listing;

use Supernova\AtRestFilter\Http\SearchRequest;
use Supernova\AtRestFilter\Helper\Template;

class FamilyNoticeCreateListing {
    private SearchRequest $search;
    private Template $templateHelper;
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
        add_shortcode('at_rest_family_notice_create_listing', array($this, 'render_shortcode'));
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
        $user_id = get_current_user_id();
        $per_page_array = [
            6,
            12,
            18,
            24,
            30,
        ];
        $this->initJs();
        ob_start();
        include AT_REST_FILTER_DIR . '/views/listing/family-notice-create.php';
        return ob_get_clean();
    }
    private function initJs() {

        $this->templateHelper->connectMainCSS();
        $this->templateHelper->connectListingJsSettings();
        $this->templateHelper->connectTippy();
        wp_enqueue_script('at-rest-filter-family-notice-create-functions', 
            AT_REST_FILTER_URL . 'js/functions/family-notice-create-functions.js', 
            array('at-rest-post-listing-js'), '1.0.0', true);
       
        wp_localize_script('at-rest-filter-family-notice-create-functions', 'atRestData', [
            'ajaxurl' => admin_url('admin-ajax.php'),
        ]);
    }
}