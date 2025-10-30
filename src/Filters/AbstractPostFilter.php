<?php

namespace Supernova\AtRestFilter\Filters;

use Supernova\AtRestFilter\Cache\CacheInterface;
use Supernova\AtRestFilter\Http\SearchRequest;
use Supernova\AtRestFilter\Data\DataPost;
use Supernova\AtRestFilter\QueryBuilders\QueryBuilder;
use WP_Query;

/**
 * Abstract base class for post filtering
 */
abstract class AbstractPostFilter
{

	protected CacheInterface $cache;
	protected string $postType;

	protected string $type = '';
	protected int $cacheExpiration = 3600;

	protected ?DataPost $dataPost = null;
	protected ?QueryBuilder $queryBuilder = null;


	public function __construct( CacheInterface $cache )
	{
		$this->cache = $cache;
	}

	/**
	 * Apply filter with caching
	 *
	 * @param  array $params Filter parameters.
	 * @return array Posts with pagination metadata.
	 */
	public function apply( array $params ): array
	{
		if (isset($params['type'])) {
			$this->type = sanitize_title($params['type']);
		}

		$this->setDataPost( $this->getDataPost( $params ) );
		$this->setQueryBuilder( $this->getPostQueryBuilder( $params ) );

		$cached = $this->cache->get( $params );
		if ( $cached !== false ) {
			return $cached;
		}

		$queryArgs = $this->buildQueryArgs( $params );
		$query     = new WP_Query( $queryArgs );
		$result    = $this->formatResponse( $query, $params );

		$this->cache->set( $params, $result, $this->cacheExpiration );

		return $result;
	}

	/**
	 * Build WP_Query arguments
	 * Child classes must implement this method
	 *
	 * @param  array $params Filter parameters.
	 * @return array WP_Query arguments.
	 */
	abstract protected function buildQueryArgs( array $params ): array;

	/**
	 * Get base query arguments with pagination and sorting
	 *
	 * @param  array $params Filter parameters.
	 * @return array Base WP_Query arguments.
	 */
	protected function getBaseQueryArgs( array $params ): array
	{
		$page    = isset( $params['page'] )
			? max( 1, (int) $params['page'] )
			: 1;
		$perPage = isset( $params['per_page'] )
			? max( 1, min( 100, (int) $params['per_page'] ) )
			: 10;

		$orderby = sanitize_text_field( $params['orderby'] ?? 'date' );

		$args = array(
			'post_type'      => $this->postType,
			'post_status'    => 'publish',
			'posts_per_page' => $perPage,
			'paged'          => $page,
			'order'          => $this->sanitizeOrder( $params['order'] ?? 'DESC' ),
			'fields'         => 'ids',
			'no_found_rows'  => false,
		);

		if ( $this->isStandardOrderBy( $orderby ) ) {
			$args['orderby'] = $orderby;
		} else {
			$args['orderby']  = 'meta_value';
			$args['meta_key'] = $orderby;
		}

		return $args;
	}

	protected function formatResponse( WP_Query $query, array $params ): array
	{
		$postIds = $query->posts;

		$posts = array_map(
			function ( $postId ) {
				return $this->formatPost( $postId );
			},
			$postIds
		);

		return array(
			'posts'           => $posts,
			'pagination'      => array(
				'total'        => $query->found_posts,
				'pages'        => $query->max_num_pages,
				'current_page' => max( 1, $query->get( 'paged' ) ),
				'per_page'     => (int) $query->get( 'posts_per_page' ),
			),
			'filters_applied' => $this->getAppliedFilters( $params ),
		);
	}

	/**
	 * Format single post by ID
	 * Child classes must implement this method
	 *
	 * @param  int $postId Post ID.
	 * @return array Formatted post data.
	 */
	abstract protected function formatPost( int $postId ): array;

	protected function getAppliedFilters( array $params ): array
	{
		$exclude = array( 'page', 'per_page', 'orderby', 'order' );

		return array_filter(
			$params,
			function ( $key ) use ( $exclude ) {
				return ! in_array( $key, $exclude, true );
			},
			ARRAY_FILTER_USE_KEY
		);
	}

	public function setDataPost( ?DataPost $dataPost ) {
		$this->dataPost = $dataPost;
	}
	public function setQueryBuilder( ?QueryBuilder $queryBuilder ) {
		$this->queryBuilder = $queryBuilder;
	}
	protected function getPostQueryBuilder( array $params ): ?QueryBuilder {

		$query_builder_name = $this->getQueryBuilderName();
		try {
			if (class_exists($query_builder_name)) {
				return new $query_builder_name($params);
			}
		} catch (\Throwable $e) {
			error_log("QueryBuilder error [{$query_builder_name}]: " . $e->getMessage());
		}
		return null;
	}

	protected function getDataPost( array $params ): ?DataPost {
		$data_post_name = $this->getDataPostName();
		
		try {
			if (class_exists($data_post_name)) {
				return new $data_post_name($params);
			}
		} catch (\Throwable $e) {
			error_log("DataPost error [{$data_post_name}]: " . $e->getMessage());
		}
		
		return null;
	}
	protected function getQueryBuilderName(): string {
		$type = $this->type ? $this->toClassName($this->type) : '';
		error_log('type: ' . $type);
		return 'Supernova\AtRestFilter\QueryBuilders\\' 
		. $this->toClassName($this->postType) 
		. $type
		. 'QueryBuilder';
	}
	protected function getDataPostName(): string {
		$type = $this->type ? $this->toClassName($this->type) : '';
		return 'Supernova\AtRestFilter\Data\\' 
		. $this->toClassName($this->postType) 
		. $type
		. 'DataPost';
	}
	private function toClassName(string $str): string {
		return str_replace('-', '', ucwords($str, '-'));
	}
	protected function isStandardOrderBy( string $orderby ): bool
	{
		$standard = array( 'date', 'title', 'modified', 'rand', 'menu_order', 'name' );
		return in_array( $orderby, $standard, true );
	}

	protected function sanitizeOrder( string $order ): string
	{
		$order = strtoupper( $order );
		return in_array( $order, array( 'ASC', 'DESC' ), true )
			? $order
			: 'DESC';
	}

	/**
	 * Create SearchRequest for parameter sanitization
	 *
	 * @param  array $fields Field definitions for sanitization.
	 * @return SearchRequest
	 */
	protected function createSearchRequest( array $fields ): SearchRequest
	{
		return new SearchRequest( $fields );
	}

	public function setCacheExpiration( int $seconds ): self
	{
		$this->cacheExpiration = $seconds;
		return $this;
	}
}
