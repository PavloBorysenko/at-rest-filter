<?php

namespace Supernova\AtRestFilter\Data;

class DeathNoticesCreateDataPost extends DeathNoticesDataPost {
    private $userId;
    private $params;
    public function __construct($params) {
        $this->params = $params;
        $this->userId = $params['user_id'] ?? 0;
    }
    public function getPreparedData( $postId ): array {
        $data = parent::getPreparedData( $postId );
        $data['has_map'] = get_field('select_church_county', $postId)? true: false;
        $data['status'] = get_post_status( $postId );
        $data['acf_status'] = get_field('status', $postId );
        $data['status_class'] = $this->getStatusClass( $postId, $data['status'], $data['acf_status'] );
        $data['preview_url'] = add_query_arg( 'table', 'true', get_permalink( $postId ) );
        $data['edit_link'] = add_query_arg( 'id', $postId, site_url( '/funeral-director/death-notices/create/' ) );
        return $data;
    }
    private function getStatusClass( int $postId, string $status, string $acf_status ): string {
        if ( $status === 'draft' ) {
            return 'status--draft';
        }
        if ( $acf_status === 'time' ) {
            return 'status--time';
        }
        if ( $acf_status === 'arrangement' ) {
            return 'status--arrangement';
        }
        return 'status--empty';
    }
}