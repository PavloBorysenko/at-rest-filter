<div class="at-rest-filter-form-wrapper">
    <form class="filters__form">
    <div class="form-user__field">
        <input type="text" id="filter-firstname" placeholder="First name" class="form-user__input">
        <button type="button" class="form-user__reset-btn" id="reset_firstname" style="display: none;">×</button>
    </div>

    <div class="form-user__field">
        <input type="text" id="filter-surname" placeholder="Surname" class="form-user__input">
        <button type="button" class="form-user__reset-btn" id="reset_surname" style="display: none;">×</button>
    </div>

    <div class="form-user__field">
        <input type="text" id="filter-nee" placeholder="Née" class="form-user__input">
        <button type="button" class="form-user__reset-btn" id="reset_nee" style="display: none;">×</button>
    </div>

    <div class="form-user__field">
        <select id="filter-county" class="form-user__select">
        <option value="" disabled selected>County</option>
        <?php foreach ($counties_data as $county => $towns) : ?>
            <option value="<?php echo $county; ?>"><?php echo $county; ?></option>
        <?php endforeach; ?>
        </select>
    </div>

    <div class="form-user__field">
        <select id="filter-town" disabled class="form-user__select">
        <option value="" disabled selected>Town</option>
        </select>
    </div>

    <div class="form-user__field">
        <input type="text" id="filter-date-from" placeholder="From" class="form-user__input is--date">
    </div>

    <div class="form-user__field">
        <input type="text" id="filter-date-to" placeholder="To" class="form-user__input is--date">
    </div>
    </form>
    
    <div class="filters__control">
        <button type="button" id="search-button"><span class="icon"></span> Search</button>
        <button type="button" id="clear-button" class="opacity-hover" style="display:none;"><span class="icon"></span> Clear All</button>
    </div>
    <div class="at-rest-county-data" style="display: none;">
        <?php echo wp_json_encode($counties_data); ?>
    </div>
</div>
  