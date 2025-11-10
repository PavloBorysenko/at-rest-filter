<div class="at-rest-post-listing at-rest-death-notice-listing  loop-table sort" 
    data-post-type="death-notices"
    data-type="create"
    data-user-id="<?php echo esc_attr($user_id); ?>">
    <div class="bulk-actions" style="display: none; margin-bottom: 1.875rem;">
        <button type="button" class="bulk-delete-btn">Delete selected</button>
        <select class="bulk-status-select">
          <option value="" disabled selected>Set status</option>
          <option value="__none__">No status</option>
          <option value="time">Changed</option>
          <option value="arrangement">Updated</option>
        </select>
      </div>
    <div class="funeral-homes-table facetwp-facet facetwp-facet-sort_table facetwp-type-sort family-notices-table">
        <div class="funeral-homes-table__header facetwp-sort-radio">
            <div class="funeral-homes-table__cell">
                <label class="checkbox-custom">
                    <input type="checkbox" id="bulk-select-all">
                    <span class="checkbox-custom__box"></span>
                </label>
            </div>            
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
            <div class="funeral-homes-table__cell">
                <button class="sort-button" 
                    data-sort="map" 
                    data-state="<?php echo $orderby === 'map' ? $order : 'default'; ?>">
                    Map
                </button>
            </div>
            <div class="funeral-homes-table__cell">
                <button class="sort-button" 
                    data-sort="status" 
                    data-state="<?php echo $orderby === 'status' ? $order : 'default'; ?>">
                    Status
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
    <div class="funeral-homes-table__row {{#if additional_address_county}}has-addresses{{/if}}">
        <div class="funeral-homes-table__cell">
            <label class="checkbox-custom">
                <input type="checkbox" name="bulk_select[]" value="{{id}}">
                <span class="checkbox-custom__box"></span>
            </label>
        </div>
        <a href="{{preview_url}}" 
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
        <div class="funeral-homes-table__cell">
        {{#if has_map}}

            <span class="death-notice__map-icon"></span> 
        {{/if}}
        {{#if !has_map }}
            —
        {{/if}}
        </div>
        <div class="funeral-homes-table__cell">
            {{#if status == 'draft'}}
                <span class="death-notice__status status--draft">Draft</span>
            {{/if}}
            {{#if status != 'draft'}}
                <select class="death-notice__status facetwp-ignore {{ status_class }}" data-post-id="{{id}}">
                    <option value="" {{selected acf_status ''}}>No status</option>
                    <option value="time" {{selected acf_status 'time'}}>Changed</option>
                    <option value="arrangement" {{selected acf_status 'arrangement'}}>Updated</option>
                </select>
            {{/if}}
        </div>
        <div class="funeral-homes-table__cell is--date">
            <span class="date--list">{{date publish_date}}</span>
            <span class="date--grid">{{date publish_date long}}</span>
        </div>
        
        <div class="funeral-homes-table__cell actions">
            <a href="{{edit_link}}" class="favorite-edit" data-tippy-content="Edit">
                Edit
            </a>
            <button type="button" class="favorite-delete" data-id="{{id}}" data-tippy-content="Delete" data-type="link">
                Delete
            </button>
        </div>
    </div>
</script>