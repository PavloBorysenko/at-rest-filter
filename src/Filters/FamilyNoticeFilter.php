<?php

namespace Supernova\AtRestFilter\Filters;

use Supernova\AtRestFilter\Cache\CacheInterface;

class FamilyNoticeFilter extends AbstractPostFilter {
    protected string $postType = 'family-notices';

	protected array $orderByMap = array(
		'town'   => 'select-town',
		'county' => 'select-county',
	);
    public function __construct( CacheInterface $cache ) {
        parent::__construct( $cache );
    }
    protected function buildQueryArgs( array $params ): array {
		$params = $this->mapOrderByField( $params );
		$args   = $this->getBaseQueryArgs( $params );
		$args = $this->queryBuilder->getSearchArgs( $args );
		return $args;
    }

    protected function formatPost( int $postId ): array {
		return $this->dataPost->getPreparedData( $postId );
	}
	protected function mapOrderByField( array $params ): array
	{
		if ( isset( $params['orderby'] ) && isset( $this->orderByMap[ $params['orderby'] ] ) ) {
			$params['orderby'] = $this->orderByMap[ $params['orderby'] ];
		}
		return $params;
	}	
}