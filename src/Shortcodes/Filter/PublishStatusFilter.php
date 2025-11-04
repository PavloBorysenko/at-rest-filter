<?php

namespace Supernova\AtRestFilter\Shortcodes\Filter;

use Supernova\AtRestFilter\Http\SearchRequest;
use Supernova\AtRestFilter\Helper\Template;

class PublishStatusFilter {
    private SearchRequest $search;
    private Template $templateHelper;
    public function __construct() {
        $this->search = new SearchRequest([
            'pub' => 'string',
            'status' => 'string',
        ]);
        $this->templateHelper = new Template();
        $this->init();
    }
    public function init() {
        add_shortcode('at_rest_publish_status_filter', array($this, 'render_shortcode'));
    }
    public function render_shortcode($atts = []) {
        $atts = shortcode_atts([
            'post_type' => 'death-notices',
        ], $atts);
        $search = $this->search;
        $this->initJs();
        ob_start();
        include AT_REST_FILTER_DIR . '/views/filter/publish-status-filter.php';
        return ob_get_clean();
    }
    private function initJs() {
        $this->templateHelper->connectChoices();
        $this->templateHelper->connectUrlManager();
        
        wp_enqueue_script('at-rest-publish-status-filter-js', AT_REST_FILTER_URL . 'js/filters/notices-publish-status-filter.js', array('choices-js', 'at-rest-url-manager-js'), '1.0.1', true);
    }
}