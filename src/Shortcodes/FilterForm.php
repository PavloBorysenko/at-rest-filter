<?php

namespace Supernova\AtRestFilter\Shortcodes;

use Supernova\AtRestFilter\Data\County as CountyData; 

class FilterForm {

    private $county_data;
    public function __construct() {
        $this->county_data = new CountyData();
        $this->init();
    }
    public function init() {
        add_shortcode('at_rest_filter', array($this, 'render_form'));
    }
    public function render_form() {
        $counties_data = $this->county_data->getCountiesData();
        ob_start();
        include AT_REST_FILTER_DIR . '/views/filter/form.php';
        return ob_get_clean();
    }
}