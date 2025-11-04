<?php

namespace Supernova\AtRestFilter\QueryBuilders;

class FamilyNoticesCreateQueryBuilder extends FamilyNoticesQueryBuilder {
    protected $userId;
    protected $params;
    public function __construct($params) {
        $this->params = $params;
        $this->userId = $params['user_id'] ?? 0;
    }
    public function getSearchArgs( array $args ): array {
        $args = parent::getSearchArgs( $args );

        if (!empty($this->params['status'])) {
            $args['post_status'] = $this->params['status'];
        } else {
            $args['post_status'] = ['draft', 'payment', 'pending', 'publish', 'overdue'];
        }

        $args['author'] = $this->userId;
        return $args;
    }
}