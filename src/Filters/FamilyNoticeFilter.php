<?php

namespace Supernova\AtRestFilter\Filters;

use Supernova\AtRestFilter\Cache\CacheInterface;

class FamilyNoticeFilter extends DeathNoticeFilter {
    protected string $postType = 'family-notices';
    public function __construct( CacheInterface $cache ) {
        parent::__construct( $cache );
    }
    protected function buildQueryArgs( array $params ): array {
        $args = parent::buildQueryArgs( $params );

		if ( ! empty( $params['notice_type'] ) ) {
			$args['meta_query'][] = array(
				'key'     => 'notice_type',
				'value'   => $params['notice_type'],
				'compare' => '=',
			);
		}
		error_log(print_r($params, true));
        return $args;
    }

    protected function formatPost( int $postId ): array {
		return array(
			'id'                        => $postId,
			'title'                     => get_the_title( $postId ),
			'link'                      => get_permalink( $postId ),
			'publish_date'              => get_the_date( 'c', $postId ),
			'image'                     => $this->getImage( $postId ),
			'county'                    => $this->getFieldValue( 'select-county', $postId ),
			'town'                      => $this->getFieldValue( 'select-town', $postId ),
			'additional_address_county' => $this->getAdditionalField( $postId, 'additional_address_county' ),
			'additional_address_town'   => $this->getAdditionalField( $postId, 'additional_address_town' ),
			'notice_type'               => $this->getFieldValue( 'notice_type', $postId ),
		);
	}
}