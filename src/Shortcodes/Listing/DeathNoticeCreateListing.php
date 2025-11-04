<?php

namespace Supernova\AtRestFilter\Shortcodes\Listing;

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
        $this->templateHelper->connectMainCSS();

        // Enqueue our custom scripts with proper dependencies
        $this->templateHelper->connectListingJsSettings();
        $this->templateHelper->connectTippy();

        wp_enqueue_script('at-rest-filter-death-notice-create-functions', 
            AT_REST_FILTER_URL . 'js/functions/death-notice-create-functions.js', 
            array('at-rest-post-listing-js'), '1.0.0', true);
       
        wp_localize_script('at-rest-filter-death-notice-create-functions', 'atRestData', [
            'ajaxurl' => admin_url('admin-ajax.php'),
        ]);
    }
}
