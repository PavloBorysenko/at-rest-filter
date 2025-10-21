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
        $this->dequeueFilterJs();
        $this->initJs();

        $counties_data = $this->county_data->getCountiesData();
        $search = $this->search;
        ob_start();
        include AT_REST_FILTER_DIR . '/views/filter/form.php';
        return ob_get_clean();
    }

    private function dequeueFilterJs() {
        wp_dequeue_script('filter-js');
        wp_deregister_script('filter-js');
    }
    private function initJs() {
        wp_enqueue_style('flatpickr-css', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css', array(), '4.6.13');
        wp_enqueue_script('flatpickr-js', 'https://cdn.jsdelivr.net/npm/flatpickr', array(), '4.6.13', true);

        wp_enqueue_style('choices-css', 'https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css', array(), '1.0.1');
        wp_enqueue_script('choices-js', 'https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js', array(), '1.0.1', true);
       
        wp_enqueue_script('at-rest-url-manager-js', AT_REST_FILTER_URL . 'js/url-manager.js', array(), '1.0.1', true);
        wp_enqueue_script('at-rest-filter-notices-js', AT_REST_FILTER_URL . 'js/notices-search-form.js', array('flatpickr-js', 'at-rest-url-manager-js', 'choices-js'), '1.2.2', true);
    }
}