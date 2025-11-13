<div class="at-rest-post-listing at-rest-death-notice-photo-condolences-listing loop-table sort" 
    data-post-type="death-notices"
    data-type="photo-condolences"
    data-user-id="<?php echo $user_id; ?>"
>
    <?php $template_helper->drawSpinner(); ?>
    <div class="funeral-homes-table family-notices-table facetwp-facet facetwp-type-sort">
        <div class="funeral-homes-table__header facetwp-sort-radio">
    		<div class="funeral-homes-table__cell">
        		<button class="sort-button" 
                    data-sort="name" 
                    data-state="<?php echo $orderby === 'name' ? $order : 'default'; ?>">
                    Name
                </button>
    		</div>
    		<div class="funeral-homes-table__cell">
        		<button class="sort-button" 
                data-sort="date" 
                data-state="<?php echo $orderby === 'date' ? $order : 'default'; ?>">
                Date</button>
    		</div>
    		<div class="funeral-homes-table__cell">
        		<button class="sort-button" 
                data-sort="allow_condolences" 
                data-state="<?php echo $orderby === 'allow_condolences' ? $order : 'default'; ?>">
                Condolences On/Off</button>
    		</div>
    		<div class="funeral-homes-table__cell">Condolence list</div>
    		<div class="funeral-homes-table__cell">Photo list</div>
    		<div class="funeral-homes-table__cell"></div>
		</div>

        <div class="facetwp-template">
            <div class="funeral-homes-table__body">
            </div>
        </div>
    </div>
    <?php $template_helper->drawPagination($per_page_array); ?>
    <?php $template_helper->drawEmptyState(); ?>
</div>
<script type="text/template" id="death-notices-list-template">
    <div class="funeral-homes-table__row {{#if additional_address_county}}has-addresses{{/if}}">
        <a href="{{death_notice_link}}" 
           class="funeral-homes-table__cell column-name death-notice__link {{#if icon}}with-icon{{/if}}">
            <div class="death-notice__name">
                {{image image thumbnail}}
                <span>{{title}}</span>
            </div>
        </a>
        
        <div class="funeral-homes-table__cell is--date">
            <span class="date--list">{{date publish_date}}</span>
            <span class="date--grid">{{date publish_date long}}</span>
        </div>
        <div class="funeral-homes-table__cell">
            <div class="toggler__wrap">
                <input data-post-id="{{id}}" type="checkbox" class="is--toggler" {{checked allow_condolences}} >
                <div class="toggler__icon"><span></span></div>
            </div>
        </div>
        <div class="funeral-homes-table__cell">
            {{#if comments_count}}
                <a href="{{condolences_link}}" class="is--show-btn">+{{display_comments}}</a>
            {{/if}}
            {{#if !comments_count }}
                <div class="is--show-btn">0</div>
            {{/if}}
        </div>
        <div class="funeral-homes-table__cell">
            {{#if gallery_count}}
                <a href="{{gallery_link}}" target="_blank" class="is--show-btn">+{{display_gallery}}</a>
            {{/if}}
            {{#if !gallery_count }}
                <div class="is--show-btn">0</div>
            {{/if}}
        </div>
        <div class="funeral-homes-table__cell">
            <a href="{{death_notice_link}}" target="_blank" class="is--btn bordered">Go to Death Notice</a>
        </div>       

    </div>
</script>