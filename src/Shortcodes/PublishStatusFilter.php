<?php

namespace Supernova\AtRestFilter\Shortcodes;

use Supernova\AtRestFilter\Http\SearchRequest;

class PublishStatusFilter {
    private $search;
    public function __construct() {
        $this->search = new SearchRequest([
            'pub' => 'string',
            'status' => 'string',
        ]);
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
        wp_enqueue_style('choices-css', 'https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css', array(), '1.0.1');
        wp_enqueue_script('choices-js', 'https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js', array(), '1.0.1', true);
        wp_enqueue_script('at-rest-url-manager-js', AT_REST_FILTER_URL . 'js/url-manager.js', array(), '1.0.1', true);
        wp_enqueue_script('at-rest-publish-status-filter-js', AT_REST_FILTER_URL . 'js/notices-publish-status-filter.js', array('choices-js', 'at-rest-url-manager-js'), '1.0.1', true);
    }
}