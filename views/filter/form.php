<div class="at-rest-filter-form-wrapper form col-5">
    <form class="filters__form" data-search-type="<?php echo esc_attr($atts['post_type']); ?>">
        <div class="form-user__field">
            <input  type="text" 
                    name="firstname" 
                    id="filter-firstname" 
                    placeholder="First name" 
                    class="form-user__input" 
                    value="<?php echo esc_attr($search->get('firstname', '')); ?>"
            >
            <button type="button" 
                    class="form-user__reset-btn" 
                    id="reset_firstname" 
                    style="display: none;"
            >×</button>
        </div>

        <div class="form-user__field">
            <input  type="text" 
                    name="surname" 
                    id="filter-surname" 
                    placeholder="Surname" 
                    class="form-user__input" 
                    value="<?php echo esc_attr($search->get('surname', '')); ?>"
            >
            <button type="button" 
                    class="form-user__reset-btn" 
                    id="reset_surname" 
                    style="display: none;"
            >×</button>
        </div>

        <div class="form-user__field">
            <input  type="text" 
                    name="nee" 
                    id="filter-nee" 
                    placeholder="Née" 
                    class="form-user__input" 
                    value="<?php echo esc_attr($search->get('nee', '')); ?>"
            >
            <button type="button" 
                    class="form-user__reset-btn" 
                    id="reset_nee" 
                    style="display: none;"
            >×</button>
        </div>

        <div class="form-user__field">
            <select id="filter-county" name="county" class="form-user__select">
            <option value="" disabled <?php selected($search->get('county'), ''); ?>>County</option>
            <?php foreach ($counties_data as $county => $towns) : ?>
                <option value="<?php echo esc_attr($county); ?>" 
                    <?php selected($search->get('county'), $county); ?>>
                    <?php echo esc_html($county); ?>
                </option>
            <?php endforeach; ?>
            </select>
            <button type="button" 
                    class="form-user__reset-btn" 
                    id="reset_county" 
                    style="display: none;"
            >×</button>
        </div>

        <div class="form-user__field">
            <select id="filter-town" name="town" class="form-user__select">
            <option value="" disabled selected>Town</option>
            <?php 
            $selectedCounty = $search->get('county');
            if ($selectedCounty && isset($counties_data[$selectedCounty])) : 
                foreach ($counties_data[$selectedCounty] as $town) : ?>
                    <option value="<?php echo esc_attr($town); ?>" 
                        <?php selected($search->get('town'), $town); ?>>
                        <?php echo esc_html($town); ?>
                    </option>
                <?php endforeach;
            endif; ?>
            </select>
            <button type="button" 
                    class="form-user__reset-btn" 
                    id="reset_town" 
                    style="display: none;"
            >×</button>
        </div>

        <div class="form-user__field">
            <input  type="text" 
                    name="from" 
                    id="filter-from" 
                    placeholder="From" 
                    class="form-user__input is--date" 
                    value="<?php echo esc_attr($search->get('from', '')); ?>"
            >
        </div>

        <div class="form-user__field">
            <input  type="text" 
                    name="to" 
                    id="filter-to" 
                    placeholder="To" 
                    class="form-user__input is--date" 
                    value="<?php echo esc_attr($search->get('to', '')); ?>"
            >
        </div>
        <button type="submit" style="display: none;"></button>
    </form>
    
    <div class="filters__control">
        <button type="button" id="search-button"><span class="icon"></span> Search</button>
        <button type="button" 
                id="clear-button" 
                class="opacity-hover" 
                style="display:none;">
            <span class="icon"></span> Clear All
        </button>
    </div>
    <div class="at-rest-county-data" style="display: none;">
        <?php echo wp_json_encode($counties_data); ?>
    </div>
</div>
  