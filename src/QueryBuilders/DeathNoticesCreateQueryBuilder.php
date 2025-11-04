<?php

namespace Supernova\AtRestFilter\QueryBuilders;

class DeathNoticesCreateQueryBuilder extends DeathNoticesQueryBuilder {
    protected $userId;
    protected $params;
    public function __construct($params) {
        $this->params = $params;
        $this->userId = $params['user_id'] ?? 0;
    }
    public function getSearchArgs( array $args ): array {

        $args = parent::getSearchArgs( $args );
        $metaQuery = [];

        if (!empty($this->params['pub'])  && ($this->params['pub'] === 'draft' || $this->params['pub'] === 'publish')) {
            $args['post_status'] = $this->params['pub'];
        } else {
            $args['post_status'] = ['publish', 'draft'];
        }
        
        if (!empty($this->params['status'])) {
            if ($this->params['status'] === 'none') {
                $metaQuery[] = [
                    'key' => 'status',
                    'value' => '',
                    'compare' => '=',
                ];
            } else {
                $metaQuery[] = [
                    'key' => 'status',
                    'value' => $this->params['status'],
                    'compare' => '=',
                ];
            }
        }

        if ( isset( $args['meta_query'] ) && is_array( $args['meta_query'] ) ) {
            $relation = $args['meta_query']['relation'] ?? 'AND';
    
            $existingMeta = $args['meta_query'];
            unset( $existingMeta['relation'] );
    
            $args['meta_query'] = array_merge(
                ['relation' => $relation],
                $existingMeta,
                $metaQuery
            );
        } else {
            $args['meta_query'] = array_merge( ['relation' => 'AND'], $metaQuery );
        }
        $args['author'] = $this->userId;
        return $args;
    }
}