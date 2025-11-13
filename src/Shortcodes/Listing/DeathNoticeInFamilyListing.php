<?php

namespace Supernova\AtRestFilter\Shortcodes\Listing;



class DeathNoticeInFamilyListing extends DeathNoticeListing {
    public function init() {
        add_shortcode('at_rest_death_notice_listing_in_family', array($this, 'render_shortcode'));
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
        include AT_REST_FILTER_DIR . '/views/listing/death-notice-in-family.php';
        return ob_get_clean();
    }

    protected function initJs() {
        parent::initJs();
        wp_enqueue_script('at-rest-filter-listing-in-family-functions', 
        AT_REST_FILTER_URL . 'js/functions/listing-in-family-functions.js', 
        array(), '1.0.0', true);
    }
}