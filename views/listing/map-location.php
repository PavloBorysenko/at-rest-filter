<div class="at-rest-post-listing at-rest-map-location-listing at-rest-death-notice-listing sort loop-table" 
data-user-id="<?php echo esc_attr($user_id); ?>" 
data-type=""
data-post-type="map-location">

    <div class="funeral-homes-table facetwp-facet facetwp-facet-sort_table facetwp-type-sort family-notices-table">
        <div class="funeral-homes-table__header facetwp-sort-radio">
            <div class="funeral-homes-table__cell"></div>
            <div class="funeral-homes-table__cell">
                <button class="sort-button" 
                    data-sort="category" 
                    data-state="<?php echo $orderby === 'category' ? $order : 'default'; ?>">
                    Category
                </button>
            </div>
            <div class="funeral-homes-table__cell">
                <button class="sort-button" 
                    data-sort="name" 
                    data-state="<?php echo $orderby === 'name' ? $order : 'default'; ?>">
                    Name
                </button>
            </div>
            <div class="funeral-homes-table__cell">
                <button class="sort-button" 
                    data-sort="county" 
                    data-state="<?php echo $orderby === 'county' ? $order : 'default'; ?>">
                    County
                </button>
            </div>
            <div class="funeral-homes-table__cell">
                <button class="sort-button" 
                    data-sort="town" 
                    data-state="<?php echo $orderby === 'town' ? $order : 'default'; ?>">
                    Town
                </button>
            </div>
            <div class="funeral-homes-table__cell">
                <button class="sort-button" 
                    data-sort="post_status" 
                    data-state="<?php echo $orderby === 'post_status' ? $order : 'default'; ?>">
                    Status
                </button>
            </div>
            <div class="funeral-homes-table__cell"></div>
        </div>
        <div class="facetwp-template">
            <div class="funeral-homes-table__body"></div>
        </div>
    </div>
    <?php $template_helper->drawPagination($per_page_array); ?>
    <?php $template_helper->drawEmptyState(); ?>
</div>
<script type="text/template" id="map-location-list-template">
    <div class="funeral-homes-table__row">
        <div class="funeral-homes-table__cell">
        {{#if !is_owner }}
            <div class="favorite-toggle {{ fav_class }}"
                 data-type="map-location"
                 data-post-id="{{post_id}}"
                 role="button"
                 aria-label="Toggle favorite"></div>
        {{/if}}
        </div>
        <div class="funeral-homes-table__cell">
            {{empty denomination}}
        </div>
        <div class="funeral-homes-table__cell">
            <div class="death-notice__name">
                <span>{{name}}</span>
            </div>
        </div>
        <div class="funeral-homes-table__cell">
            {{empty county}}
        </div>
        <div class="funeral-homes-table__cell">
            {{empty town}}
        </div>
        <div class="funeral-homes-table__cell">
            {{#if is_published}}         
                Published
            {{/if}}
            {{#if !is_published}}
                <span class="death-notice__status status--draft">Draft</span>
            {{/if}}
        </div>
        <div class="funeral-homes-table__cell">
            <a href="{{view_link}}" class="is--btn bordered">View</a>

            {{#if is_owner}}
            <a href="{{edit_link}}"
            class="favorite-edit"
            data-tippy-content="Edit">Edit</a>
            <button type="button"
                    class="favorite-delete"
                    data-id="{{post_id}}"
                    data-tippy-content="Delete"
                    data-type="link">Delete</button>
            {{/if}}
        </div>
    </div>
</script>
