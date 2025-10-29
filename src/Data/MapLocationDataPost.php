<?php

namespace Supernova\AtRestFilter\Data;

class MapLocationDataPost implements DataPost {

    private $params;
    private $userId = 0;

    public function __construct($params) {
        $this->params = $params;
        $this->userId = $params['user_id'] ?? 0;
    }
    public function getPreparedData( $postId ): array {
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