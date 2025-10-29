<div class="at-rest-publish-filter form is--table-sort" data-search-type="<?php echo esc_attr($atts['post_type']); ?>">
    <div class="death-notice__filters-group">
    <select id="custom_pub" name="pub">
        <?php foreach ($options as $key => $value) : ?>
            <option value="<?php echo esc_attr($key); ?>" <?php selected($search->get('pub'), $key); ?>><?php echo esc_html($value); ?></option>
        <?php endforeach; ?>
    </select>
    </div>
</div>