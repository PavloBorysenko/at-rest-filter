<div class="at-rest-statistic-search-form form is--search"
    data-search-type="<?php echo esc_attr($post_type); ?>"
>
    <input
        type="text"
        name="_statistic_search"
        class="form-user__input"
        id="customStatisticSearch"
        placeholder="Type here to search"
        value="<?php echo esc_html($search); ?>"
    />
    <button
        type="button"
        id="search-button"
        class="form-user__button search"
    >
        <span class="icon"></span> Search
    </button>
    <a href="/funeral-director/statistic/"  
        id="clear-button" 
        class="opacity-hover">
        <span class="icon"></span> 
        Clear All
    </a>
</div>