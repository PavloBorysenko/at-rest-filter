<?php

namespace Supernova\AtRestFilter\Shortcodes;

use Supernova\AtRestFilter\Http\SearchRequest;

class NoticesStatisticSearch {
    private SearchRequest $search;
    public function __construct() {
        $this->search = new SearchRequest([
            '_statistic_search' => 'string',
        ]);
        $this->init();
    }
    public function init() {
        add_shortcode('at_rest_notices_statistic_search', array($this, 'render_shortcode'));
    }
    public function render_shortcode($atts = []) {
        $atts = shortcode_atts([
            'post_type' => 'death-notices',
        ], $atts);
        $search = $this->search->get('_statistic_search');
        $post_type = $atts['post_type'];
        $this->initJs();
        ob_start();
        include AT_REST_FILTER_DIR . '/views/filter/statistic-search.php';
        return ob_get_clean();
    }
    private function initJs() {
        wp_enqueue_script('at-rest-url-manager-js', 
            AT_REST_FILTER_URL . 'js/url-manager.js', 
            array(), 
            '1.0.1', 
            true);
        wp_enqueue_script('at-rest-statistic-search-js', 
            AT_REST_FILTER_URL . 'js/statistic-search.js', 
            array('at-rest-url-manager-js'), 
            '1.0.1', 
            true);
    }
}