<div class="at-rest-post-status-filter form is--table-sort" data-search-type="<?php echo esc_attr($atts['post_type']); ?>">
    <div class="death-notice__filters-group">
        <select id="custom_status">
            <?php foreach ($statuses as $key => $status) : ?>
                <option value="<?php echo $key; ?>"><?php echo $status; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>