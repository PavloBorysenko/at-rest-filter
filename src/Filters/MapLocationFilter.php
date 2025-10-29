<?php

namespace Supernova\AtRestFilter\Filters;

class MapLocationFilter extends AbstractPostFilter {
    protected string $postType = 'map-location';

    private $userId;

    protected function buildQueryArgs(array $params): array {
        if (isset($params['user_id'])) {
            $this->userId = intval($params['user_id']);
        }
        
        $args = $this->getBaseQueryArgs($params);

        if (!empty($params['pub'])  && ($params['pub'] === 'draft' || $params['pub'] === 'publish')) {
            $args['post_status'] = $params['pub'];
        } else {
            $args['post_status'] = ['publish', 'draft'];
        }

        if (!empty($params['church_name'])) {
            $args['s'] = sanitize_text_field($params['church_name']);
        }

        $meta_query = ['relation' => 'AND'];

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

    protected function formatPost(int $postId): array {

        $is_favorite = $this->getIsFavorite($postId, $this->userId);
        $data = array(
            'post_id' => $postId,
            'user_id' => $this->userId ?? 0,
            'name' => get_the_title($postId),
            'edit_link' => $this->getEditLink($postId),
            'view_link' => $this->getViewLink($postId),
            'is_author' => $this->userId === get_post_field('post_author', $postId),
            'is_auto' => get_post_meta($postId, '_auto_created', true) == '1',
            'is_favorite' => $is_favorite,
            'fav_class' => $is_favorite ? 'is-active' : '',
            'is_published' => get_post_status($postId) === 'publish',
            'county' => $this->getFieldValue('county', $postId),
            'town' => $this->getFieldValue('town', $postId),
            'denomination' => $this->getFieldValue('category', $postId),
            'is_owner' => $this->isOwner($postId),
            'status' => get_post_status($postId),
        );

        return $data;
    }

    private function normalizeValue(string $value): string {
        return strtolower(str_replace(' ', '-', sanitize_text_field($value)));
    }

    private function getFieldValue( string $fieldName, int $postId ): string {
		$value = get_field( $fieldName, $postId );
		
		if ( ! $value || in_array( $value, array( 'Select County', 'Select Town' ), true ) ) {
			return '';
		}
        if (strtolower($value ) === 'cemetery-crematoria') {
            return 'Cemetery & Crematoria';
        }

		return ucwords( str_replace( '-', ' ', $value ) );
	}
    private function isOwner( int $postId): bool {
        return $this->userId === get_post_field('post_author', $postId) && !get_post_meta($postId, '_auto_created', true) == '1';
    }
    private function getIsFavorite( int $postId, int $userId ): bool {
        if (!$userId) {
            return false;
        }
        $favorite_locations = get_user_meta($userId, 'favorite_map_locations', true);
        if (!is_array($favorite_locations)) $favorite_locations = [];

        $is_favorite = !empty($favorite_locations[$postId]['is_favorite']);
        return $is_favorite;
    }

    private function getFavoriteIds( int $userId ): array {
        $favorite_locations = get_user_meta($userId, 'favorite_map_locations', true);
        if (!is_array($favorite_locations)) $favorite_locations = [];
        $favorite_ids = array_keys(array_filter($favorite_locations, fn($f) => !empty($f['is_favorite'])));
        return $favorite_ids;
    }
    private function getEditLink( int $postId ): string {
        return esc_url(add_query_arg('id', $postId, $this->getMapsCreateUrl()));
    }
    private function getViewLink( int $postId ): string {
        return esc_url(add_query_arg(['id' => $postId, 'view' => 1], $this->getMapsCreateUrl()));
    }
    private function getMapsCreateUrl(): string {
        return site_url('/funeral-director/maps/create/');
    }

}