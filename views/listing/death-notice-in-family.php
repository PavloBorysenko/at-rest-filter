<div class="at-rest-post-listing at-rest-death-notice-listing-in-family loop-table sort"
    data-post-type="death-notices"
    data-type="family">
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
            <div class="funeral-homes-table__cell column-date">
                <button class="sort-button" 
                    data-sort="date" 
                    data-state="<?php echo $orderby === 'date' ? $order : 'default'; ?>">
                    Published
                </button>
            </div>
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
    <div class="funeral-homes-table__row {{ifHasAddresses additional_addresses}}">
        <a href="{{link}}" 
           class="funeral-homes-table__cell column-name death-notice__link {{#if icon}}with-icon{{/if}}">
            <div class="death-notice__name">
                {{image image thumbnail}}
                <span class="is--name">{{title}}</span>
            </div>
            {{#if icon}}{{icon icon}}{{/if}}
        </a>
        
        <div class="funeral-homes-table__cell column-county is--county">
            <div class="list-item">{{empty county}}</div>
            {{#each additional_addresses}}
            <div class="list-item">{{empty this.county}}</div>
            {{/each}}
        </div>
        
        <div class="funeral-homes-table__cell column-town">
            <div class="list-item">{{empty town}}</div>
            {{#each additional_addresses}}
            <div class="list-item">{{empty this.town}}</div>
            {{/each}}
        </div>
        
        <div class="funeral-homes-table__cell is--date">
            <span class="date--list">{{date publish_date}}</span>
            <span class="date--grid notice__date--full">{{date publish_date long}}</span>
        </div>
        
        <div class="funeral-homes-table__cell is--actions">
            <a href="{{ preview_link }}" target="_blank" 
            class="death-notice__preview-btn breakdance-link button-atom button-atom--secondary bde-button__button is--link" 
            data-id="{{ id }}">
                Preview
            </a>
            <a type="button" 
                class="death-notice__select-btn breakdance-link button-atom button-atom--primary bde-button__button" 
                data-id="{{ id }}">
                Select
            </a>
        </div>
    </div>
</script>



