<?php
/**
 * Plugin Name: AT Rest Filter
 * Description: A plugin to filter Posts
 * Version: 1.0.0
 * Author: Na-Gora&totoroko
 */


define('AT_REST_FILTER_CACHE_ACTIVE', true);
define('AT_REST_FILTER_DIR', __DIR__);
define('AT_REST_FILTER_URL', plugin_dir_url(__FILE__));

// Include autoloader
require_once AT_REST_FILTER_DIR . '/vendor/autoload.php';

// Initialize cache factory (creates isolated caches per post type)
$cacheFactory = new \Supernova\AtRestFilter\Cache\CacheFactory('at-rest-', AT_REST_FILTER_CACHE_ACTIVE);

// Initialize filter service
$filterService = new \Supernova\AtRestFilter\Api\PostFilterService($cacheFactory);
$filterService->registerFilter('death-notices', \Supernova\AtRestFilter\Filters\DeathNoticeFilter::class);
$filterService->registerFilter('family-notices', \Supernova\AtRestFilter\Filters\FamilyNoticeFilter::class);
$filterService->registerFilter('map-location', \Supernova\AtRestFilter\Filters\MapLocationFilter::class);

$cacheManager = new \Supernova\AtRestFilter\Cache\CacheManager(
    $cacheFactory,
    ['death-notices', 'family-notices', 'map-location']
);
$cacheManager->registerAutoClearing();

// Initialize REST API
$restApi = new \Supernova\AtRestFilter\Api\RestApiController($filterService);
$restApi->register();

// Initialize shortcodes
new \Supernova\AtRestFilter\Shortcodes\Filter\MainForm();
new \Supernova\AtRestFilter\Shortcodes\Listing\DeathNoticeListing();
new \Supernova\AtRestFilter\Shortcodes\Listing\FamilyNoticeListing();
new \Supernova\AtRestFilter\Shortcodes\ViewSelect();
new \Supernova\AtRestFilter\Shortcodes\Filter\TypeFilter();
// FD
new \Supernova\AtRestFilter\Shortcodes\Filter\MapFilterForm();
new \Supernova\AtRestFilter\Shortcodes\Listing\MapLocationListing();
new \Supernova\AtRestFilter\Shortcodes\Filter\PublishFilter();
new \Supernova\AtRestFilter\Shortcodes\Listing\DeathNoticeCreateListing();
new \Supernova\AtRestFilter\Shortcodes\Filter\PublishStatusFilter();
new \Supernova\AtRestFilter\Shortcodes\Listing\FamilyNoticeCreateListing();
new \Supernova\AtRestFilter\Shortcodes\Filter\PostStatusFilter();
new \Supernova\AtRestFilter\Shortcodes\Filter\NoticesStatisticSearch();
new \Supernova\AtRestFilter\Shortcodes\Listing\NoticesStatisticListing();
new \Supernova\AtRestFilter\Shortcodes\Listing\DeathNoticePhotoCondolences();