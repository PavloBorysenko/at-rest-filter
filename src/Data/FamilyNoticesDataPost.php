<?php
 
 namespace Supernova\AtRestFilter\Data;

 class FamilyNoticesDataPost implements DataPost {
    public function getPreparedData( $postId ): array {
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
 }