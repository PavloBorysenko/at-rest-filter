<div class="at-rest-publish-status-filter form is--table-sort" 
data-search-type="<?php echo esc_attr($atts['post_type']); ?>">
    <div class="death-notice__filters-group">
    <select id="custom_status">
        <option value="" <?php selected($search->get('status'), ''); ?>>All Statuses</option>
        <option value="none" <?php selected($search->get('status'), 'none'); ?>>No status</option>
        <option value="time" <?php selected($search->get('status'), 'time'); ?>>Changed</option>
        <option value="arrangement" <?php selected($search->get('status'), 'arrangement'); ?>>Later</option>
    </select>
    </div>

    <div class="death-notice__filters-group">
    <select id="custom_pub">
        <option value="" <?php selected($search->get('pub'), ''); ?>>All</option>
        <option value="publish" <?php selected($search->get('pub'), 'publish'); ?>>Published</option>
        <option value="draft" <?php selected($search->get('pub'), 'draft'); ?>>Draft</option>
    </select>
    </div>
</div>