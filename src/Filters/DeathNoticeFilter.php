<?php

namespace Supernova\AtRestFilter\Filters;

use Supernova\AtRestFilter\Cache\CacheInterface;

class DeathNoticeFilter extends AbstractNoticeFilter
{

	protected string $postType = 'death-notices';

	public function __construct( CacheInterface $cache )
	{
		parent::__construct( $cache );
	}

	protected function formatPost( int $postId ): array
	{
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
			'icon'                      => $this->getStatusIcon( $postId ),
		);
	}

	private function getStatusIcon( int $postId ): ?array
	{
		$status = get_field( 'status', $postId );

		if ( 'time' === $status ) {
			return array(
				'type'    => 'time',
				'tooltip' => get_field( 'time_badge', 'option' ) ?: 'Time has changed',
			);
		}

		if ( 'arrangement' === $status ) {
			return array(
				'type'    => 'arrangement',
				'tooltip' => get_field( 'warning_badge', 'option' ) ?: 'Arrangements have changed',
			);
		}

		return null;
	}

}

