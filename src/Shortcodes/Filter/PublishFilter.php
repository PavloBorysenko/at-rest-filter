<?php

namespace Supernova\AtRestFilter\Shortcodes\Filter;

use Supernova\AtRestFilter\Http\SearchRequest;
use Supernova\AtRestFilter\Helper\Template;

class PublishFilter {
    private SearchRequest $search;
    private Template $templateHelper;
    public function __construct() {
        $this->search = new SearchRequest([
            'pub' => 'string',
        ]);
        $this->templateHelper = new Template();
        $this->init();
    }
    public function init() {
        add_shortcode('at_rest_publish_filter', array($this, 'render_shortcode'));
    }
    public function render_shortcode($atts = []) {
        $atts = shortcode_atts([
            'post_type' => 'map-location',
        ], $atts);
        $this->initJs();
        $search = $this->search;
        $options = [
            '' => 'All',
            'favorites' => 'Favorites',
            'createbyme' => 'Created by me',
            'draft' => 'Draft',
            'publish' => 'Published',
        ];
        ob_start();
        include AT_REST_FILTER_DIR . '/views/filter/publish-filter.php';
        return ob_get_clean();
    }
    private function initJs() {
        $this->templateHelper->connectChoices();
        $this->templateHelper->connectUrlManager();
        wp_enqueue_script('at-rest-publish-filter-js', AT_REST_FILTER_URL . 'js/filters/map-publish-filter.js', array('choices-js', 'at-rest-url-manager-js'), '1.0.2', true);
    }
}