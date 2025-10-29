<?php

namespace Supernova\AtRestFilter\Filters;

use Supernova\AtRestFilter\Cache\CacheInterface;
class DeathNoticeCreateFilter extends DeathNoticeFilter {
    protected string $postType = 'death-notices';
    public function __construct( CacheInterface $cache ) {
        parent::__construct( $cache );
    }
    protected function formatPost( int $postId ): array {
        $data = parent::formatPost( $postId );
        $data['has_map'] = get_field('select_church_county', $postId)? true: false;
        $data['status'] = get_post_status( $postId );
        $data['acf_status'] = get_field('status', $postId );
        $data['status_class'] = $this->getStatusClass( $postId, $data['status'], $data['acf_status'] );
        $data['preview_url'] = add_query_arg( 'table', 'true', get_permalink( $postId ) );
        $data['edit_link'] = add_query_arg( 'id', $postId, site_url( '/funeral-director/death-notices/create/' ) );
        return $data;
    }
    protected function buildQueryArgs( array $params ): array {
        $args = parent::buildQueryArgs( $params );
        $args['author'] = $params['user_id'] ?? 0;
        return $args;
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