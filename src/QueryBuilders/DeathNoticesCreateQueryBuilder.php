<?php

namespace Supernova\AtRestFilter\QueryBuilders;

class DeathNoticesCreateQueryBuilder extends DeathNoticesQueryBuilder {
    private $userId;
    private $params;
    public function __construct($params) {
        $this->params = $params;
        $this->userId = $params['user_id'] ?? 0;
    }
    public function getSearchArgs( array $args ): array {
        $args = parent::getSearchArgs( $args );
        if (!empty($params['pub'])  && ($params['pub'] === 'draft' || $params['pub'] === 'publish')) {
            $args['post_status'] = $params['pub'];
        } else {
            $args['post_status'] = ['publish', 'draft'];
        }
       // $args['author'] = $this->userId;
        return $args;
    }
}