<?php

namespace Supernova\AtRestFilter\Shortcodes;

use Supernova\AtRestFilter\Data\County as CountyData; 
use Supernova\AtRestFilter\Http\SearchRequest;

class FilterForm {

    private $county_data;
    private SearchRequest $search;

    public function __construct() {
        $this->county_data = new CountyData();
        $this->search = new SearchRequest([
            'firstname' => 'string',
            'surname' => 'string', 
            'nee' => 'string',
            'county' => 'string',
            'town' => 'string',
            'from' => 'date',
            'to' => 'date',
            'search_type' => 'string'
        ]);
        $this->init();
    }

    public function init() {
        add_shortcode('at_rest_filter', array($this, 'render_form'));
    }

    public function render_form($atts = []) {
        $atts = shortcode_atts([
            'post_type' => 'death-notices',
        ], $atts);
        $this->initJs();

        $counties_data = $this->county_data->getCountiesData();
        $search = $this->search;
        ob_start();
        include AT_REST_FILTER_DIR . '/views/filter/form.php';
        return ob_get_clean();
    }

    private function initJs() {
        wp_enqueue_style('flatpickr-css', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css', array(), '4.6.13');
        wp_enqueue_script('flatpickr-js', 'https://cdn.jsdelivr.net/npm/flatpickr', array(), '4.6.13', true);
        wp_enqueue_script('at-rest-filter-notices-js', AT_REST_FILTER_URL . 'js/notices-search-form.js', array('flatpickr-js'), '1.0.0', true);
    }
}