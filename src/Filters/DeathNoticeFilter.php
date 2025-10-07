<?php

namespace Supernova\AtRestFilter\Filters;

use Supernova\AtRestFilter\Cache\CacheInterface;

class DeathNoticeFilter extends AbstractPostFilter
{

	protected string $postType = 'death-notices';

	private array $orderByMap = array(
		'town'   => 'select-town',
		'county' => 'select-county',
	);

	public function __construct( CacheInterface $cache )
	{
		parent::__construct( $cache );
	}

	private function mapOrderByField( array $params ): array
	{
		if ( isset( $params['orderby'] ) && isset( $this->orderByMap[ $params['orderby'] ] ) ) {
			$params['orderby'] = $this->orderByMap[ $params['orderby'] ];
		}
		return $params;
	}

	protected function buildQueryArgs( array $params ): array
	{
		$params = $this->mapOrderByField( $params );
		$args   = $this->getBaseQueryArgs( $params );

		$metaQuery = array( 'relation' => 'AND' );

		if ( ! empty( $params['firstname'] ) ) {
			$metaQuery[] = array(
				'key'     => 'firstname',
				'value'   => $params['firstname'],
				'compare' => 'LIKE',
			);
		}

		if ( ! empty( $params['surname'] ) ) {
			$metaQuery[] = array(
				'key'     => 'surname',
				'value'   => $params['surname'],
				'compare' => 'LIKE',
			);
		}

		if ( ! empty( $params['nee'] ) ) {
			$metaQuery[] = array(
				'key'     => 'nee',
				'value'   => $params['nee'],
				'compare' => 'LIKE',
			);
		}

		if ( ! empty( $params['county'] ) ) {
			$metaQuery[] = array(
				'key'     => 'select-county',
				'value'   => $params['county'],
				'compare' => '=',
			);
		}

		if ( ! empty( $params['town'] ) ) {
			$metaQuery[] = array(
				'key'     => 'select-town',
				'value'   => $params['town'],
				'compare' => '=',
			);
		}

		if ( ! empty( $params['from'] ) ) {
			$metaQuery[] = array(
				'key'     => 'funeral_date',
				'value'   => $this->convertDateToYmd( $params['from'] ),
				'compare' => '>=',
				'type'    => 'DATE',
			);
		}

		if ( ! empty( $params['to'] ) ) {
			$metaQuery[] = array(
				'key'     => 'funeral_date',
				'value'   => $this->convertDateToYmd( $params['to'] ),
				'compare' => '<=',
				'type'    => 'DATE',
			);
		}

		if ( count( $metaQuery ) > 1 ) {
			$args['meta_query'] = $metaQuery;
		}

		return $args;
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

	private function getImage( int $postId ): ?array
	{
		$image = get_field( 'image', $postId );
		
		if ( ! $image ) {
			return null;
		}

		return array(
			'url'       => $image['url'] ?? '',
			'thumbnail' => $image['sizes']['thumbnail'] ?? $image['url'] ?? '',
			'alt'       => $image['alt'] ?? get_the_title( $postId ),
		);
	}

	private function getFieldValue( string $fieldName, int $postId ): string
	{
		$value = get_field( $fieldName, $postId );
		
		if ( ! $value || in_array( $value, array( 'Select County', 'Select Town' ), true ) ) {
			return '';
		}

		return ucwords( str_replace( '-', ' ', $value ) );
	}

	private function getAdditionalField( int $postId, string $fieldName ): array
	{
		$addresses = get_field( 'additional_address', $postId );
		
		if ( ! $addresses || ! is_array( $addresses ) ) {
			return array();
		}

		$values = array();
		foreach ( $addresses as $row ) {
			if ( ! empty( $row[ $fieldName ] ) ) {
				$values[] = ucwords( str_replace( '-', ' ', $row[ $fieldName ] ) );
			}
		}

		return array_unique( array_filter( $values ) );
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

	private function convertDateToYmd( string $date ): string
	{
		$parts = explode( '.', $date );
		if ( count( $parts ) === 3 ) {
			return sprintf( '%04d-%02d-%02d', $parts[2], $parts[1], $parts[0] );
		}
		return $date;
	}
}

