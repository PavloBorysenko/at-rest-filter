<?php 

namespace Supernova\AtRestFilter\Shortcodes\Filter;

use Supernova\AtRestFilter\Data\Denomination;
use Supernova\AtRestFilter\Data\County;
use Supernova\AtRestFilter\Http\SearchRequest;
use Supernova\AtRestFilter\Helper\Template;
class MapFilterForm {
    private Denomination $denomination_data;
    private County $county_data;
    private SearchRequest $search;
    private Template $templateHelper;
    public function __construct() {
        $this->search = new SearchRequest([
            'denomination' => 'string',
            'county' => 'string',
            'church_name' => 'string',
        ]);
        $this->templateHelper = new Template();
        $this->denomination_data = new Denomination();
        $this->county_data = new County();
        $this->init();
    }
    public function init() {
        add_shortcode('at_rest_map_filter_form', array($this, 'render_shortcode'));
    }
    public function render_shortcode($atts = []) {
        $atts = shortcode_atts([
            'post_type' => 'map-location',
        ], $atts);
        $denominations_data = $this->denomination_data->getDenominationsData();
        $counties_data = $this->county_data->getCountiesOnly();
        $search = $this->search;
        $this->dequeueFilterJs();
        $this->initJs();
        ob_start();
        include AT_REST_FILTER_DIR . '/views/filter/map-filter-form.php';
        return ob_get_clean();
    }
    private function dequeueFilterJs() {
        wp_dequeue_script('filter-js');
        wp_deregister_script('filter-js');
    }
    private function initJs() {
        $this->templateHelper->connectChoices();
        $this->templateHelper->connectUrlManager(); 
        wp_enqueue_script('at-rest-map-filter-js', AT_REST_FILTER_URL . 'js/filters/map-filter-form.js', array( 'choices-js', 'at-rest-url-manager-js'), '1.0.1', true);
    }
}