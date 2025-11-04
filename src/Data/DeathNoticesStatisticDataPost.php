<?php

namespace Supernova\AtRestFilter\Data;

class DeathNoticesStatisticDataPost implements DataPost {
    public function getPreparedData( $postId ): array {
        return array(
            'id' => $postId,
            'title' => get_the_title($postId),
            'date' => $this->getFormattedDate(get_the_date('c', $postId)),
            'total_views' => get_post_meta($postId, 'total_views', true),   
            'today_views' => (string)$this->getTodayViews($postId),
        );
    }

    private function getTodayViews( int $postId ): int {
        $today_key = 'views_' . date('Ymd');
        return (int) get_post_meta($postId, $today_key, true);
    }

    private function getFormattedDate( string $date ): string {
        return date('d/m/Y H:i:s', strtotime($date));
    }
}