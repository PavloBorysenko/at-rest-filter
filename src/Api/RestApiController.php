<?php

namespace Supernova\AtRestFilter\Api;

use Supernova\AtRestFilter\Http\SearchRequest;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

class RestApiController
{

	private PostFilterService $service;
	private string $namespace = 'at-rest/v1';

	public function __construct( PostFilterService $service )
	{
		$this->service = $service;
	}

	public function register(): void
	{
		add_action( 'rest_api_init', array( $this, 'registerRoutes' ) );
	}

	public function registerRoutes(): void
	{
		register_rest_route(
			$this->namespace,
			'/posts/filter',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'filterPosts' ),
				'permission_callback' => '__return_true',
				'args'                => $this->getQueryArgs(),
			)
		);
	}

	public function filterPosts( WP_REST_Request $request )
	{
		$postType = $request->get_param( 'post_type' );

		if ( empty( $postType ) ) {
			return new WP_Error(
				'missing_post_type',
				'Parameter "post_type" is required',
				array( 'status' => 400 )
			);
		}

		$params = $request->get_params();

		$result = $this->service->filter( $postType, $params );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return new WP_REST_Response( $result, 200 );
	}

	private function getQueryArgs(): array
	{
		return array(
			'post_type' => array(
				'required'          => true,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'page'      => array(
				'type'              => 'integer',
				'default'           => 1,
				'sanitize_callback' => 'absint',
			),
			'per_page'  => array(
				'type'              => 'integer',
				'default'           => 10,
				'sanitize_callback' => 'absint',
			),
			'orderby'   => array(
				'type'              => 'string',
				'default'           => 'date',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'order'     => array(
				'type'              => 'string',
				'default'           => 'DESC',
				'sanitize_callback' => 'sanitize_text_field',
			),
		);
	}
}

