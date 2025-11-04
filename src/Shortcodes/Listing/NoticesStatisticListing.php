<?php

namespace Supernova\AtRestFilter\Shortcodes\Listing;

use Supernova\AtRestFilter\Http\SearchRequest;
use Supernova\AtRestFilter\Helper\Template;

class NoticesStatisticListing {
    private SearchRequest $search;
    private Template $templateHelper;

    private string $type = 'statistic';
    public function __construct() {
        $this->templateHelper = new Template();
        $this->search = new SearchRequest(
            [
                'per-page' => 'int',
                'pg' => 'int',
                'orderby' => 'string',
                'order' => 'string',
            ]
        );
        $this->init();
    }
    public function init() {
        add_shortcode('at_rest_notices_statistic_listing', array($this, 'render_shortcode'));
    }
    public function render_shortcode($atts = []) {
        $atts = shortcode_atts([
            'post_type' => 'death-notices',
        ], $atts);
        if (!is_user_logged_in()) {
            return 'Please log in.';
        }
        $post_type = $atts['post_type'];
        $template_helper = $this->templateHelper;
        $per_page = $this->search->get('per-page') ?? $atts['per_page'];
        $orderby = $this->search->get('orderby') ?? $atts['orderby'];
        $order = $this->search->get('order') ?? $atts['order'];
        $search = $this->search;
        $type = $this->type;
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
        include AT_REST_FILTER_DIR . '/views/listing/notices-statistic.php';
        return ob_get_clean();
    }
    private function initJs() {
        $this->templateHelper->connectMainCSS();
        $this->templateHelper->connectListingJsSettings();
        // Enqueue our custom scripts with proper dependencies
        wp_enqueue_script('at-rest-filter-notices-statistic-listing-functions', 
            AT_REST_FILTER_URL . 'js/functions/notices-statistic-listing-functions.js', 
            array('at-rest-post-listing-js'), '1.0.0', true);
    }
}