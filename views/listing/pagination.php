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
