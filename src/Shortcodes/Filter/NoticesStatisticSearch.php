<?php

namespace Supernova\AtRestFilter\Shortcodes\Filter;

use Supernova\AtRestFilter\Http\SearchRequest;
use Supernova\AtRestFilter\Helper\Template;

class NoticesStatisticSearch {
    private SearchRequest $search;

    private Template $templateHelper;
    public function __construct() {
        $this->templateHelper = new Template();
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
        $this->templateHelper->connectUrlManager();
        wp_enqueue_script('at-rest-statistic-search-js', 
            AT_REST_FILTER_URL . 'js/filters/statistic-search.js', 
            array('at-rest-url-manager-js'), 
            '1.0.1', 
            true);
    }
}