<?php

namespace Supernova\AtRestFilter\Shortcodes\Filter;

use Supernova\AtRestFilter\Http\SearchRequest;
use Supernova\AtRestFilter\Helper\Template;

class TypeFilter {

    private array $types = [];
    private SearchRequest $search;

    private Template $templateHelper;
    public function __construct() {
        $this->templateHelper = new Template();
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
        $this->templateHelper->connectChoices();
        $this->templateHelper->connectUrlManager();
        
        wp_enqueue_script('at-rest-type-filter-js', AT_REST_FILTER_URL . 'js/filters/notices-type-filter.js', 
        array('choices-js', 'at-rest-url-manager-js'), '1.2.2', true);
    }
}