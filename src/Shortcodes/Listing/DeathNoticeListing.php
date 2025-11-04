<?php

namespace Supernova\AtRestFilter\Shortcodes\Listing;

use Supernova\AtRestFilter\Http\SearchRequest;
use Supernova\AtRestFilter\Helper\Template;
class DeathNoticeListing {

    protected SearchRequest $search;
    protected Template $templateHelper;
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
        // Enqueue notice listing CSS
        $this->templateHelper->connectMainCSS();
        // Enqueue our custom scripts with proper dependencies
        $this->templateHelper->connectListingJsSettings();
        // Enqueue Tippy.js library
        $this->templateHelper->connectTippy();
    }
}


