<?php

namespace Supernova\AtRestFilter\Data;

class DeathNoticesFamilyDataPost extends DeathNoticesDataPost {
    public function getPreparedData( $postId ): array {
        $data = parent::getPreparedData( $postId );
        $data['preview_link'] = add_query_arg( 'table', 'true', get_permalink( $postId ) );
        return $data;
    }
}