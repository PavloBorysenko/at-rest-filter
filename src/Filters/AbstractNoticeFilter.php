<?php

namespace Supernova\AtRestFilter\Filters;

use Supernova\AtRestFilter\Cache\CacheInterface;

abstract class AbstractNoticeFilter extends AbstractPostFilter {
    protected string $postType;
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

		$metaQuery = array( 'relation' => 'AND' );

		if ( ! empty( $params['firstname'] ) ) {
			$metaQuery[] = array(
				'key'     => 'name',
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
			$countyQuery = array(
				'relation' => 'OR',
				array(
					'key'     => 'select-county',
					'value'   => $params['county'],
					'compare' => '=',
				),
			);
			
			for ( $i = 0; $i < 3; $i++ ) {
				$countyQuery[] = array(
					'key'     => "additional_address_{$i}_additional_address_county",
					'value'   => $params['county'],
					'compare' => '=',
				);
			}
			
			$metaQuery[] = $countyQuery;
		}

		if ( ! empty( $params['town'] ) ) {
			$townQuery = array(
				'relation' => 'OR',
				array(
					'key'     => 'select-town',
					'value'   => $params['town'],
					'compare' => '=',
				),
			);
			
			for ( $i = 0; $i < 3; $i++ ) {
				$townQuery[] = array(
					'key'     => "additional_address_{$i}_additional_address_town",
					'value'   => $params['town'],
					'compare' => '=',
				);
			}
			
			$metaQuery[] = $townQuery;
		}


		if ( ! empty( $params['from'] ) ) {
			$args['date_query'][] = array(
				'after'     => $this->convertDateToYmd( $params['from'] ),
				'inclusive' => true,
			);
		}

		if ( ! empty( $params['to'] ) ) {
			$args['date_query'][] = array(
				'before'    => $this->convertDateToYmd( $params['to'] ),
				'inclusive' => true,
			);
		}

		if ( count( $metaQuery ) > 1 ) {
			$args['meta_query'] = $metaQuery;
		}

		return $args;
	}
	protected function mapOrderByField( array $params ): array
	{
		if ( isset( $params['orderby'] ) && isset( $this->orderByMap[ $params['orderby'] ] ) ) {
			$params['orderby'] = $this->orderByMap[ $params['orderby'] ];
		}
		return $params;
	}
    protected function getImage( int $postId ): ?array {
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
	protected function getFieldValue( string $fieldName, int $postId ): string {
		$value = get_field( $fieldName, $postId );
		
		if ( ! $value || in_array( $value, array( 'Select County', 'Select Town' ), true ) ) {
			return '';
		}

		return ucwords( str_replace( '-', ' ', $value ) );
	}

	protected function getAdditionalField( int $postId, string $fieldName ): array {
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
	protected function convertDateToYmd( string $date ): string { 
		$parts = explode( '.', $date );
		if ( count( $parts ) === 3 ) {
			return sprintf( '%04d-%02d-%02d', $parts[2], $parts[1], $parts[0] );
		}
		return $date;
	}    
}