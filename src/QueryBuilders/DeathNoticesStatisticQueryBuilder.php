<?php

namespace Supernova\AtRestFilter\QueryBuilders;

class DeathNoticesStatisticQueryBuilder implements QueryBuilder {
    protected $userId;
    protected $params;
    public function __construct($params) {
        $this->params = $params;
        $this->userId = $params['user_id'] ?? -1;
    }
    public function getSearchArgs( array $args ): array {

        if (isset($this->params['_statistic_search']) && !empty($this->params['_statistic_search'])) {
            $args['s'] = $this->params['_statistic_search'];
        }

        $args = $this->addSortByViews($args);

        $args['post_status'] = ['publish', 'draft'];
        $args['author'] = $this->userId;

        return $args;
    }

    private function addSortByViews( array $args ): array {
        if (isset($args['meta_key']) ) {
            if ($args['meta_key'] === 'total_views') {
                $args['orderby'] = 'meta_value_num';
            } else if ($args['meta_key'] === 'views_today') {
                unset($args['meta_key']);
                
                $today_key = $this->getTodayKey();
                $order_direction = $args['order'] ?? 'DESC';
                unset($args['order']);
                
                $args['orderby'] = 'views_today_custom';
                $args['_views_today_key'] = $today_key;
                $args['_views_today_order'] = $order_direction;
                
                add_filter('posts_join', function($join, $query) use ($today_key) {
                    global $wpdb;
                    
                    if ($query->get('orderby') === 'views_today_custom') {
                        $join .= " LEFT JOIN {$wpdb->postmeta} AS mt_views ON {$wpdb->posts}.ID = mt_views.post_id AND mt_views.meta_key = '{$today_key}'";
                    }
                    
                    return $join;
                }, 10, 2);
                
                add_filter('posts_orderby', function($orderby, $query) use ($order_direction) {
                    global $wpdb;
                    
                    if ($query->get('orderby') === 'views_today_custom') {
                        $orderby = "CAST(COALESCE(mt_views.meta_value, 0) AS SIGNED) {$order_direction}";
                    }
                    
                    return $orderby;
                }, 10, 2);
            }
        }
        return $args;
    }

    private function getTodayKey(  ): string {
        return 'views_' . date('Ymd');
    }
}