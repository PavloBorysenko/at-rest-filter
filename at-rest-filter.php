<?php
/**
 * Plugin Name: AT Rest Filter
 * Description: A plugin to filter Posts
 * Version: 1.0.0
 * Author: Na-Gora
 */


define('AT_REST_FILTER_DIR', __DIR__);
define('AT_REST_FILTER_URL', plugin_dir_url(__FILE__));

// Include autoloader
require_once AT_REST_FILTER_DIR . '/vendor/autoload.php';

// Initialize cache
$cache = new \Supernova\AtRestFilter\Cache\TransientCache();

// Initialize filter service
$filterService = new \Supernova\AtRestFilter\Api\PostFilterService($cache);
$filterService->registerFilter('death-notices', \Supernova\AtRestFilter\Filters\DeathNoticeFilter::class);
$filterService->registerFilter('family-notices', \Supernova\AtRestFilter\Filters\FamilyNoticeFilter::class);
$filterService->registerFilter('map-location', \Supernova\AtRestFilter\Filters\MapLocationFilter::class);

// Initialize REST API
$restApi = new \Supernova\AtRestFilter\Api\RestApiController($filterService);
$restApi->register();

// Initialize shortcodes
new \Supernova\AtRestFilter\Shortcodes\FilterForm();
new \Supernova\AtRestFilter\Shortcodes\DeathNoticeListing();
new \Supernova\AtRestFilter\Shortcodes\FamilyNoticeListing();
new \Supernova\AtRestFilter\Shortcodes\ViewSelect();
new \Supernova\AtRestFilter\Shortcodes\TypeFilter();
// FD
new \Supernova\AtRestFilter\Shortcodes\MapFilterForm();
new \Supernova\AtRestFilter\Shortcodes\MapLocationListing();
new \Supernova\AtRestFilter\Shortcodes\PublishFilter();
new \Supernova\AtRestFilter\Shortcodes\DeathNoticeCreateListing();
new \Supernova\AtRestFilter\Shortcodes\PublishStatusFilter();
new \Supernova\AtRestFilter\Shortcodes\FamilyNoticeCreateListing();
new \Supernova\AtRestFilter\Shortcodes\PostStatusFilter();
new \Supernova\AtRestFilter\Shortcodes\NoticesStatisticSearch();
new \Supernova\AtRestFilter\Shortcodes\NoticesStatisticListing();
new \Supernova\AtRestFilter\Shortcodes\Listing\DeathNoticePhotoCondolences();