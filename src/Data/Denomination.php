<?php

namespace Supernova\AtRestFilter\Data;

use Supernova\AtRestFilter\Cache\TransientCache;

class Denomination {
    private $cache;

    public function __construct() {
        $this->cache = new TransientCache('at-rest-filter-denomination');
    }
    public function getDenominationsData() : array {
        $denominations_data = $this->cache->get('denominations_data');
        if ($denominations_data) {
            return $denominations_data;
        }
        $denominations_data = $this->getDenominationsDataFromDB();
        $this->cache->set('denominations_data', $denominations_data, DAY_IN_SECONDS);
        return $this->getDenominationsDataFromDB();
    }
    private function getDenominationsDataFromDB() : array {
        global $wpdb;

        $results = $wpdb->get_col("
            SELECT DISTINCT meta_value 
            FROM {$wpdb->postmeta}
            WHERE meta_key LIKE 'denomination\_%\_denomination-name'
            AND meta_value != ''
        ");
    
        if ( ! empty( $results ) ) {
            return $results;
        }
        return [];
    }
}