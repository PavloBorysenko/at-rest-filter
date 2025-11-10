<div class="at-rest-post-listing at-rest-notices-statistic-listing loop-table sort" 
    data-post-type="<?php echo $post_type; ?>"
    data-type="<?php echo $type; ?>"
    data-user-id="<?php echo $user_id; ?>"
>
    <div id="count-text-container"></div>
    <div class="funeral-homes-table facetwp-facet facetwp-facet-sort_table facetwp-type-sort family-notices-table">
        <div class="funeral-homes-table__header facetwp-sort-radio">
    		<div class="funeral-homes-table__cell">
        		<button class="sort-button" 
                data-sort="name" 
                data-state="<?php echo $orderby === 'name' ? $order : 'default'; ?>">
                Name</button>
    		</div>
    		<div class="funeral-homes-table__cell">
        		<button class="sort-button" 
                data-sort="date" 
                data-state="<?php echo $orderby === 'date' ? $order : 'default'; ?>">
                Published</button>
    		</div>
    		<div class="funeral-homes-table__cell">
        		<button class="sort-button" 
                data-sort="total_views" 
                data-state="<?php echo $orderby === 'total_views' ? $order : 'default'; ?>">
                Total views</button>
    		</div>
    		<div class="funeral-homes-table__cell">
        		<button class="sort-button" 
                data-sort="views_today" 
                data-state="<?php echo $orderby === 'views_today' ? $order : 'default'; ?>">
                Today views</button>
    		</div>
		</div>
        <div class="facetwp-template funeral-homes-table__body">

        </div> 
    </div>         
    <?php $template_helper->drawPagination($per_page_array); ?>
    <?php $template_helper->drawEmptyState(); ?>
</div>
<script type="text/template" id="<?php echo $post_type; ?>-list-template">
    <div class="funeral-homes-table__row">
        <div class="funeral-homes-table__cell">{{title}}</div>
        <div class="funeral-homes-table__cell">{{date}}</div>
        <div class="funeral-homes-table__cell">{{total_views}}</div>
        <div class="funeral-homes-table__cell">{{today_views}}</div>
    </div>
</script>
<script type="text/template" id="<?php echo $post_type; ?>-count-text-template">
    <h2 class="facetwp-search-title">{{count}} {{result_word}} for "<span class="facetwp-search-query">{{search_query}}</span>"</h2>
</script>