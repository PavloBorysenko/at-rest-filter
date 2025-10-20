
<div class="at-rest-death-notice-listing loop-table sort">
    <div class="at-rest-loading-overlay" >
        <div class="at-rest-loading-spinner">
            <svg width="50" height="50" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                <circle cx="25" cy="25" r="20" fill="none" stroke="#1e1e1e" stroke-width="4" stroke-linecap="round" stroke-dasharray="31.415 31.415" transform="rotate(-90 25 25)">
                    <animateTransform attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="1.5s" repeatCount="indefinite"/>
                </circle>
            </svg>
        </div>
    </div>
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
        </div>

        <div class="facetwp-template">
          <div class="funeral-homes-table__body">

          </div>
        </div>
    </div>

      <div class="facetwp-pagination-controls">
        <div class="custom-per-page is--per-page">
          <select id="per-page-select">
            <?php foreach ($per_page_array as $per_page) { ?>
              <option value="<?php echo $per_page; ?>"><?php echo $per_page; ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="custom-pagination is--pagination">
          <button class="pagination-btn first" data-action="first" title="First page">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
              <path d="M8.354 1.646a.5.5 0 0 1 0 .708L2.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
              <path d="M12.354 1.646a.5.5 0 0 1 0 .708L6.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
            </svg>
          </button>
          <button class="pagination-btn prev" data-action="prev" title="Previous page">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
              <path d="M8.354 1.646a.5.5 0 0 1 0 .708L2.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
            </svg>
          </button>
          <div class="pagination-numbers"></div>
          <button class="pagination-btn next" data-action="next" title="Next page">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
              <path d="M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708z"/>
            </svg>
          </button>
          <button class="pagination-btn last" data-action="last" title="Last page">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
              <path d="M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708z"/>
              <path d="M3.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L9.293 8 3.646 2.354a.5.5 0 0 1 0-.708z"/>
            </svg>
          </button>
        </div>
    </div>
    <div class="is--empty-state" style="display:none;">
      <?php echo do_shortcode('[breakdance_block blockId=4755]'); ?>
    </div>
    <div class="is--empty-state-empty" style="display:none;">
        <?php echo do_shortcode('[breakdance_block blockId=4751]'); ?>
    </div>    
</div>
<script type="text/template" id="death-notice-list-template">
    <div class="funeral-homes-table__row {{#if additional_address_county}}has-addresses{{/if}}">
        <a href="{{link}}" 
           class="funeral-homes-table__cell column-name death-notice__link {{#if icon}}with-icon{{/if}}">
            <div class="death-notice__name">
                {{image image thumbnail}}
                <span>{{title}}</span>
            </div>
            {{#if icon}}{{icon icon}}{{/if}}
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
        
        <div class="funeral-homes-table__cell is--date">
            <span class="date--list">{{date publish_date}}</span>
            <span class="date--grid">{{date publish_date long}}</span>
        </div>
        
        <div class="funeral-homes-table__cell is--residence">
            <ul>
                <li>{{empty town}}, {{empty county}}</li>
            </ul>
        </div>
    </div>
</script>



