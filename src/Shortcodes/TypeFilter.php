<?php

namespace Supernova\AtRestFilter\Shortcodes;

use Supernova\AtRestFilter\Http\SearchRequest;

class TypeFilter {

    private $types = [];
    private SearchRequest $search;
    public function __construct() {
        $this->types = [
            "Memorial Mass",
            "Memorial Service",
            "Birthday Memorial",
            "Anniversary",
            "Acknowledgement",
            "Month’s Mind"
        ];
        $this->search = new SearchRequest([
            'notice_type' => 'string',
        ]);
        $this->init();
    }
    public function init() {
        add_shortcode('at_rest_type_filter_family_notices', array($this, 'render_shortcode'));
    }
    public function render_shortcode($atts = []) {
        $atts = shortcode_atts([
            'post_type' => 'family-notices',
        ], $atts);
        $types = $this->types;
        $search = $this->search;
        $this->initJs();
        ob_start();
        include AT_REST_FILTER_DIR . '/views/filter/type-filter-family-notices.php';
        return ob_get_clean();
    }
    private function initJs() {
        wp_enqueue_style('choices-css', 'https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css', array(), '1.0.1');
        wp_enqueue_script('choices-js', 'https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js', array(), '1.0.1', true);
        wp_enqueue_script('at-rest-url-manager-js', AT_REST_FILTER_URL . 'js/url-manager.js', array(), '1.0.1', true);
        wp_enqueue_script('at-rest-type-filter-js', AT_REST_FILTER_URL . 'js/notices-type-filter.js', 
        array('choices-js', 'at-rest-url-manager-js'), '1.2.2', true);
    }
}