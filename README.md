# AT Rest Filter

WordPress plugin for filtering and displaying posts with caching.

## Cache Control

Toggle cache via constant AT_REST_FILTER_CACHE_ACTIVE in `at-rest-filter.php`:

## Shortcodes

### Search & Filter

**`[at_rest_filter]`**
Main search form for death/family notices.

-   `hide_items` - '' (default). Filter elements to hide. Use comma to separate them: firstname,secondname,nee,county,town,from,to
-   `days` - 0 (default). Prefilter by last n days

**`[at_rest_map_filter_form]`**
Search form for map locations (denomination, county, church name).

**`[at_rest_notices_statistic_search]`**
Search form for statistics.

-   `post_type` - death-notices (default)

**`[at_rest_type_filter_family_notices]`**
Type filter dropdown for family notices.

**`[at_rest_publish_filter]`**
Filter by publish status (all, favorites, draft, published).

**`[at_rest_publish_status_filter]`**
Publish status filter for create listings.

**`[at_rest_post_status_filter]`**
Post status filter.

---

### Listings

**`[at_rest_death_notice_listing]`**
Display death notices list/grid.

-   `per_page` - 6 (default)
-   `orderby` - date (default)
-   `order` - desc (default)

**`[at_rest_family_notice_listing]`**
Display family notices list/grid.

-   `per_page` - 6
-   `orderby` - date
-   `order` - desc

**`[at_rest_map_location_listing]`**
Display map locations (requires login).

-   `per_page` - 6
-   `orderby` - date
-   `order` - desc

**`[at_rest_death_notice_create]`**
Death notices created by current user (requires login).

-   `per_page` - 6
-   `orderby` - date
-   `order` - desc

**`[at_rest_family_notice_create_listing]`**
Family notices created by current user (requires login).

-   `per_page` - 6
-   `orderby` - date
-   `order` - desc

**`[at_rest_notices_statistic_listing]`**
Display statistics listing (requires login).

**`[at_rest_death_notice_photo_condolences]`**
Display death notices with photo/condolences (requires login).

-   `per_page` - 6
-   `orderby` - date
-   `order` - desc

---

### UI Components

**`[at_rest_view_select]`**
Toggle between list and grid view.

## REST API

**Endpoint:** `/wp-json/at-rest/v1/posts/filter`

**Parameters:**

-   `post_type` - required (death-notices, family-notices, map-location)
-   `page` - 1 (default)
-   `per_page` - 6 (default)
-   `orderby` - date (default)
-   `order` - DESC (default)
-   Additional filters based on post type

## Cache Architecture

-   Isolated cache per post type
-   Automatic clearing on post save/delete
-   TTL: 3600 seconds (1 hour)
-   Storage: WordPress transients

## Requirements

-   PHP 8.0+
-   WordPress 5.0+

## Pages

-   death-notices - `[at_rest_filter days=7] [at_rest_view_select] [at_rest_death_notice_listing]`
-   family-notices - `[at_rest_filter days=7 post_type=family-notices][at_rest_view_select][at_rest_type_filter_family_notices][at_rest_family_notice_listing]`
-   funeral-director/maps/ - `[at_rest_map_filter_form][at_rest_publish_filter][at_rest_map_location_listing]`
-   funeral-director/death-notices/ - `[at_rest_filter][at_rest_publish_status_filter][at_rest_death_notice_create]`
-   funeral-director/family-notices/ - `[at_rest_filter post_type="family_notice"][at_rest_type_filter_family_notices][at_rest_post_status_filter][at_rest_family_notice_create_listing]`
-   funeral-director/photo-and-condolence/ - `[at_rest_filter hide_items='county,town'][at_rest_death_notice_photo_condolences]`
-   funeral-director/statistic/ - `[at_rest_notices_statistic_search][at_rest_notices_statistic_listing]`
