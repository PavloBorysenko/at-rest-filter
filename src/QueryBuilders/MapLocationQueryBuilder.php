<?php

namespace Supernova\AtRestFilter\QueryBuilders;

class MapLocationQueryBuilder implements QueryBuilder {
    private $params;
    private $userId = 0;
    public function __construct($params) {
        $this->params = $params;
        $this->userId = $params['user_id'] ?? 0;
    }
    public function getSearchArgs( array $args ): array {

        $params = $this->params ?? [];

        $meta_query = ['relation' => 'AND'];

        if (!empty($params['pub'])  && ($params['pub'] === 'draft' || $params['pub'] === 'publish')) {
            $args['post_status'] = $params['pub'];
        } else {
            $args['post_status'] = ['publish', 'draft'];
        }
        if (!empty($params['church_name'])) {
            $args['s'] = sanitize_text_field($params['church_name']);
        }

        if (!empty($params['county'])) {
            $meta_query[] = ['key' => 'county', 'value' => $this->normalizeValue($params['county']), 'compare' => '='];
        }

        if (!empty($params['denomination'])) {
            $meta_query[] = ['key' => 'category', 'value' => $this->normalizeValue($params['denomination']), 'compare' => '='];
        }

        if (!empty($params['town'])) {
            $meta_query[] = ['key' => 'town', 'value' => $this->normalizeValue($params['town']), 'compare' => '='];
        }

        if (count($meta_query) > 1) {
            $args['meta_query'] = $meta_query;
        }
        if ($this->userId) {

            if(!empty($params['pub']) && $params['pub'] === 'favorites') {
                $args['post__in'] = $this->getFavoriteIds($this->userId);
            }

            if (!empty($params['pub']) && $params['pub'] === 'createbyme') {
                $args['author'] = (int)$this->userId;
            } else {
                $args['suppress_filters'] = false;
                $args['map_location_user_filter'] = $this->userId;
                add_filter('posts_where', [$this, 'filterPostsByUserOrAuto'], 10, 2);
            }
        } else {
            if (!isset($args['meta_query'])) {
                $args['meta_query'] = ['relation' => 'AND'];
            }
            $args['meta_query'][] = ['key' => '_auto_created', 'value' => '1', 'compare' => '='];
        }


        return $args;
    }
    private function normalizeValue(string $value): string {
        return strtolower(str_replace(' ', '-', sanitize_text_field($value)));
    }

    private function getFavoriteIds( int $userId ): array {
        $favorite_locations = get_user_meta($userId, 'favorite_map_locations', true);
        if (!is_array($favorite_locations)) $favorite_locations = [];
        $favorite_ids = array_keys(array_filter($favorite_locations, fn($f) => !empty($f['is_favorite'])));
        return $favorite_ids;
    }
    public function filterPostsByUserOrAuto($where, $query) {
        global $wpdb;
        
        if (!isset($query->query_vars['map_location_user_filter'])) {
            return $where;
        }
        
        $user_id = intval($query->query_vars['map_location_user_filter']);
        
        $where .= $wpdb->prepare(
            " AND ({$wpdb->posts}.post_author = %d OR EXISTS (
                SELECT 1 FROM {$wpdb->postmeta} 
                WHERE {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID 
                AND {$wpdb->postmeta}.meta_key = '_auto_created' 
                AND {$wpdb->postmeta}.meta_value = '1'
            ))",
            $user_id
        );
        
        remove_filter('posts_where', [$this, 'filterPostsByUserOrAuto'], 10);
        
        return $where;
    }    
}