
<div class="at-rest-death-notice-listing loop-table sort">
    <div class="funeral-homes-table facetwp-facet facetwp-facet-sort_table facetwp-type-sort family-notices-table">
        <div class="funeral-homes-table__header facetwp-sort-radio">
            <div class="funeral-homes-table__cell" style="display: none; width: 0;"></div>
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
                    data-sort="post_date" 
                    data-state="<?php echo $orderby === 'post_date' ? $order : 'default'; ?>">
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
        <div class="custom-pagination is--pagination"></div>
    </div>
</div>
<div class='at-rest-death-notice-item-template' style='display:none;'>
    <div class="funeral-homes-table__row __HAS-ADDRESSES__" >
        <a  aria-atomic=""href="__POST_LINK__"
            class="funeral-homes-table__cell column-name death-notice__link __WITH-ICON__">
            <div class="death-notice__name">
                __POST_IMAGE__
                <span class="status">__POST_STATUS__<span>
                <span>
                    __POST_TITLE__
                </span>
            </div>
            __POST_ICON__
        </a>
        <div class="funeral-homes-table__cell column-county">
            <div class="list-item">
                __COUNTY__
            </div>
            <div class="list-item">
                __EXTRA_COUNTY__
            </div>

        </div>
        <div class="funeral-homes-table__cell column-town">
            <div class="list-item">
                __TOWN__
            </div>
            <div class="list-item">
                __EXTRA_TOWN__
            </div>
        </div>
        <div class="funeral-homes-table__cell column-date is--date">
            <span class="date--list">
                __DATA_DATE__
            </span>
            <span class="date--grid">
                __POST_DATE__
            </span>
        </div>
    </div>
</div>

  <div class="is--empty-state" style="display:none;">
      <?php echo do_shortcode('[breakdance_block blockId=4755]'); ?>
  </div>
  <div class="is--empty-state-empty" style="display:none;">
      <?php echo do_shortcode('[breakdance_block blockId=4751]'); ?>
  </div>
