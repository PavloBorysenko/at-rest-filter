<?php 

namespace Supernova\AtRestFilter\Shortcodes;

class ViewSelect {
    public function __construct() {
        $this->init();
    }

    public function init() {
        add_shortcode('at_rest_view_select', array($this, 'render_shortcode'));
    }

    public function render_shortcode($atts = []) {
        $atts = shortcode_atts([
            'view' => 'list',
        ], $atts);

        $this->initJs();
        ob_start();
        include AT_REST_FILTER_DIR . '/views/listing/view-select.php';
        return ob_get_clean();
    }

    private function initJs() {
        wp_enqueue_script('view-select-js', AT_REST_FILTER_URL . 'js/view-select.js', array(), '1.0.0', true);
    }
}