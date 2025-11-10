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
            
            <svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M18.75 4.5L11.25 12L18.75 19.5M12.75 4.5L5.25 12L12.75 19.5' stroke='#AEAEAE' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/></svg>

        </button>
        <button class="pagination-btn prev" data-action="prev" title="Previous page">
            
            <svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M15.75 19.5L8.25 12L15.75 4.5' stroke='#AEAEAE' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/></svg>

        </button>
        <div class="pagination-numbers"></div>
        <button class="pagination-btn next" data-action="next" title="Next page">
            
            <svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M15.75 19.5L8.25 12L15.75 4.5' stroke='#AEAEAE' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/></svg>

        </button>
        <button class="pagination-btn last" data-action="last" title="Last page">
            
            <svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M18.75 4.5L11.25 12L18.75 19.5M12.75 4.5L5.25 12L12.75 19.5' stroke='#AEAEAE' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/></svg>

        </button>
    </div>
</div>
