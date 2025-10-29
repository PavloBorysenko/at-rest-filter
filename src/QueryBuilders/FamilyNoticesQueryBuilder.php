<?php

namespace Supernova\AtRestFilter\QueryBuilders;

class FamilyNoticesQueryBuilder extends DeathNoticesQueryBuilder {
    private $params;
    public function __construct($params) {
        $this->params = $params;
    }
    public function getSearchArgs( array $args ): array {
        $args = parent::getSearchArgs( $args );
        if ( ! empty( $this->params['notice_type'] ) ) {
			$args['meta_query'][] = array(
				'key'     => 'notice_type',
				'value'   => $this->params['notice_type'],
				'compare' => '=',
			);
		}
        return $args;
    }
}