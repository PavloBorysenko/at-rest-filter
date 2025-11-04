<?php

namespace Supernova\AtRestFilter\QueryBuilders;

class DeathNoticesPhotoCondolencesQueryBuilder extends DeathNoticesQueryBuilder {
    protected $params;
    protected $userId;
    public function __construct($params) {
        $this->params = $params;
        $this->userId = $params['user_id'] ?? 0;
    }
    public function getSearchArgs( array $args ): array {
        $args = parent::getSearchArgs( $args );
        $args['post_status'] = ['publish', 'draft'];
        $args['author'] = $this->userId;
        return $args;
    }
}