<div class="at-rest-map-filter-form form col-6-6">
    <form class="filters__form" data-search-type="<?php echo esc_attr($atts['post_type']); ?>">
    <div class="form-user__field is--fullw">
        <input type="text" name="church_name" id="filter-church_name" placeholder="Name" value="<?php echo esc_attr($search->get('church_name', '')); ?>" class="form-user__input">
        <button type="button" class="form-user__reset-btn" id="reset_church_name" style="display: none;">×</button>
    </div>

    <div class="form-user__field">
        <select id="filter-county" name="county" class="form-user__select">
        <option value="" disabled selected>All counties</option>
        <?php foreach ($counties_data as $county) : ?>
            <option value="<?php echo esc_attr($county); ?>" <?php selected($search->get('county'), $county); ?>><?php echo esc_html($county); ?></option>
        <?php endforeach; ?>
        </select>
        <button type="button" 
                class="form-user__reset-btn" 
                id="reset_county" 
                style="display: none;"
        >×</button>
    </div>
    
    <div class="form-user__field">
        <select id="filter-denomination" name="denomination" class="form-user__select">
        <option value="" disabled selected>Category</option>
        <?php foreach ($denominations_data as $denomination) : ?>
            <?php $value = str_replace('&', '', $denomination); ?>
            <option value="<?php echo esc_attr($value); ?>" <?php selected($search->get('denomination'), $value); ?>><?php echo esc_html($denomination); ?></option>
        <?php endforeach; ?>
        </select>
        <button type="button" 
                class="form-user__reset-btn" 
                id="reset_denomination" 
                style="display: none;"
        >×</button>
    </div>

    </form>
    
    <div class="filters__control">
        <button type="button" id="search-button"><span class="icon"></span> Search</button>
        <button type="button" id="clear-button" class="opacity-hover" style="display:none;"><span class="icon"></span> Clear All</button>
    </div>
</div>