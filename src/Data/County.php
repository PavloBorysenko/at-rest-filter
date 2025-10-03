<?php

namespace Supernova\AtRestFilter\Data;

use Supernova\AtRestFilter\Cache\TransientCache;

class County {

    private $cache;

    public function __construct() {
        $this->cache = new TransientCache('at-rest-filter-county');
    }
    public function getCountiesData() : array {
        $counties_data = $this->cache->get('counties_data');
        if ($counties_data) {
            return $counties_data;
        }
        $counties_data = $this->getCountiesDataFromDB();
        $this->cache->set('counties_data', $counties_data, DAY_IN_SECONDS);
        return $this->getCountiesDataFromDB();
    }
    private function getCountiesDataFromDB() : array {
        $counties_data = [];

        $database_post_ids = $this->getDatabasePostIds();
        foreach ($database_post_ids as $post_id) {
            $county_name = get_the_title($post_id);
            $counties_data[$county_name] = $this->getTownsDataFromPostId($post_id);
        }
        return $counties_data;
    }

    private function getDatabasePostIds() : array {
        $query = new \WP_Query([
            'post_type'      => 'database',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
        ]);
        
        return $query->posts;
    }

    private function getTownsDataFromPostId(int $post_id) : array {
        $towns = [];
        if (have_rows('towns', $post_id)) {
            while (have_rows('towns', $post_id)) {
                the_row();
                $town_name = get_sub_field('towns-name');
                if ($town_name) {
                    $towns[] = $town_name;
                }
            }
        }
        return $towns;
    }
}
