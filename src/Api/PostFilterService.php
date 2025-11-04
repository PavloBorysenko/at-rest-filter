<?php

namespace Supernova\AtRestFilter\Api;

use Supernova\AtRestFilter\Cache\CacheFactory;
use WP_Error;

class PostFilterService
{

	private CacheFactory $cacheFactory;
	private array $filterMap = array();

	public function __construct( CacheFactory $cacheFactory ) {
		$this->cacheFactory = $cacheFactory;
	}

	/**
	 * Register filter class for specific post type
	 *
	 * @param string $postType   Post type slug.
	 * @param string $filterClass Full filter class name.
	 */
	public function registerFilter( string $postType, string $filterClass ): void {
		$this->filterMap[ $postType ] = $filterClass;
	}

	/**
	 * Apply filter for specific post type
	 *
	 * @param  string $postType Post type slug.
	 * @param  array  $params   Filter parameters.
	 * @return array|WP_Error Filtered posts or error.
	 */
	public function filter( string $postType, array $params ) {
		if ( ! post_type_exists( $postType ) ) {
			return new WP_Error(
				'invalid_post_type',
				sprintf( 'Post type "%s" does not exist', $postType ),
				array( 'status' => 400 )
			);
		}

		if ( ! isset( $this->filterMap[ $postType ] ) ) {
			return new WP_Error(
				'filter_not_found',
				sprintf( 'No filter registered for post type "%s"', $postType ),
				array( 'status' => 404 )
			);
		}

		$filterClass = $this->filterMap[ $postType ];
		
		// Create isolated cache instance for this post type
		$cache = $this->cacheFactory->create( $postType );
		$filter = new $filterClass( $cache );

		return $filter->apply( $params );
	}

	public function getAvailablePostTypes(): array {
		return array_keys( $this->filterMap );
	}
}

