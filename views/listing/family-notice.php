<div class="at-rest-post-listing at-rest-death-notice-listing loop-table sort" data-post-type="family-notices">
    <?php $template_helper->drawSpinner(); ?>
    <div class="funeral-homes-table facetwp-facet facetwp-facet-sort_table facetwp-type-sort family-notices-table">
        <div class="funeral-homes-table__header facetwp-sort-radio">
            <div class="funeral-homes-table__cell column-name">
                <button class="sort-button" 
                    data-sort="name" 
                    data-state="<?php echo $orderby === 'name' ? $order : 'default'; ?>">
                    Name
                </button>
            </div>
            <div class="funeral-homes-table__cell column-county">
                <button class="sort-button" 
                    data-sort="county" 
                    data-state="<?php echo $orderby === 'county' ? $order : 'default'; ?>">
                    County
                </button>
            </div>
            <div class="funeral-homes-table__cell column-town">
                <button class="sort-button" 
                    data-sort="town" 
                    data-state="<?php echo $orderby === 'town' ? $order : 'default'; ?>">
                    Town
                </button>
            </div>
            <div class="funeral-homes-table__cell column-type">
                <button class="sort-button" 
                    data-sort="notice_type" 
                    data-state="<?php echo $orderby === 'notice_type' ? $order : 'default'; ?>">
                    Type
                </button>
            </div>
            <div class="funeral-homes-table__cell column-date">
                <button class="sort-button" 
                    data-sort="date" 
                    data-state="<?php echo $orderby === 'date' ? $order : 'default'; ?>">
                    Published
                </button>
            </div>
        </div>
        <div class="facetwp-template">
          <div class="funeral-homes-table__body">

          </div>
        </div>
    </div>

    <?php $template_helper->drawPagination($per_page_array); ?>
    <?php $template_helper->drawEmptyState(); ?>
</div>
<script type="text/template" id="family-notices-list-template">
    <div class="funeral-homes-table__row {{#if additional_address_county}}has-addresses{{/if}}">
        <a href="{{link}}" 
           class="funeral-homes-table__cell column-name death-notice__link {{#if icon}}with-icon{{/if}}">
            <div class="death-notice__name">
                {{image image thumbnail}}
                <span>{{title}}</span>
            </div>
        </a>
        
        <div class="funeral-homes-table__cell column-county">
            <div class="list-item">{{empty county}}</div>
            {{#each additional_address_county}}
            <div class="list-item">{{empty this}}</div>
            {{/each}}
        </div>
        
        <div class="funeral-homes-table__cell column-town">
            <div class="list-item">{{empty town}}</div>
            {{#each additional_address_town}}
            <div class="list-item">{{empty this}}</div>
            {{/each}}
        </div>
        <div class="funeral-homes-table__cell is--type">
            <span>{{notice_type}}</span>
        </div>       
        <div class="funeral-homes-table__cell is--date">
            <span class="date--list">{{date publish_date}}</span>
            <span class="date--grid">{{date publish_date long}}</span>
        </div>
    </div>
</script>