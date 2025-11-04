<?php

namespace Supernova\AtRestFilter\Shortcodes\Listing;

class FamilyNoticeListing extends DeathNoticeListing {
    public function __construct() {
        parent::__construct();
    }
    public function init() {
        add_shortcode('at_rest_family_notice_listing', array($this, 'render_shortcode'));
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
        include AT_REST_FILTER_DIR . '/views/listing/family-notice.php';
        return ob_get_clean();
    }
}