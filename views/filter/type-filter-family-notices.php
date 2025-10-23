<div class="at-rest-type-filter-family-notices form is--table-sort">
    <div class="death-notice__filters-group">
        <select name="notice_type" id="custom_type">
            <?php $selectedType = $search->get('notice_type'); ?>
            <option value="" <?php selected($selectedType, ''); ?>>All Types</option>
            <?php 
            foreach ($types as $type) {
                ?>
                <option value="<?php echo esc_attr($type); ?>" <?php selected($selectedType, $type); ?>>
                    <?php echo esc_html($type); ?>
                </option>
                <?php } ?>
        </select>
    </div>
</div>