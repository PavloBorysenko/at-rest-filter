<?php

namespace Supernova\AtRestFilter\Shortcodes\Filter;

use Supernova\AtRestFilter\Data\County as CountyData; 
use Supernova\AtRestFilter\Http\SearchRequest;
use Supernova\AtRestFilter\Helper\Template;

class MainForm {

    private CountyData $county_data;
    private SearchRequest $search;

    private Template $templateHelper;

    public function __construct() {
        $this->templateHelper = new Template();
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
            'hide_items' => '',
            'days' => 0,
        ], $atts);
        $prefiltered_data = $this->getPrefilteredData($atts);
        $this->dequeueFilterJs();
        $this->initJs();
        
        $hide_items = explode(',', $atts['hide_items']);

        $counties_data = $this->county_data->getCountiesData();
        $search = $this->search;
        ob_start();
        include AT_REST_FILTER_DIR . '/views/filter/form.php';
        return ob_get_clean();
    }

    private function getPrefilteredData($atts) {
        $prefiltered_data = [];
        if ($atts['days'] > 0) {
            $prefiltered_data = $this->setDays($atts['days']);
        }
        if (!empty($prefiltered_data)) {
            $prefiltered_data['search_type'] = $atts['post_type'];
        }
        return $prefiltered_data;
    }

    private function setDays( int$days) : array {
        if ($days > 0) {
            $today = current_time('d.m.Y');
            $from_date = date('d.m.Y', strtotime('-' . ($days - 1) . 'days', strtotime($today)));
            $to_date = $today;

            return [
                'from' => $from_date,
                'to' => $to_date,
            ];
        }
        return [];
    }
    private function dequeueFilterJs() {
        wp_dequeue_script('filter-js');
        wp_deregister_script('filter-js');
    }
    private function initJs() {
        $this->templateHelper->connectFlatpickr();
        $this->templateHelper->connectChoices();
        $this->templateHelper->connectUrlManager();
        wp_enqueue_script('at-rest-filter-notices-js', AT_REST_FILTER_URL . 'js/filters/notices-search-form.js', array('flatpickr-js', 'at-rest-url-manager-js', 'choices-js'), '1.2.2', true);
    }
}