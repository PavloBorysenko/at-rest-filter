(function () {
    let selectorFilters = {};
    let choicesFilters = {};
    selectorFilters['pub'] = document.getElementById('custom_pub');
    selectorFilters['status'] = document.getElementById('custom_status');

    if (!selectorFilters['pub'] || !selectorFilters['status']) return;

    if (typeof URLManager === 'undefined') {
        console.error('URLManager is not loaded');
        return;
    }

    const urlManager = new URLManager();

    Object.keys(selectorFilters).forEach((key) => {
        choicesFilters[key] = new Choices(selectorFilters[key], {
            shouldSort: false,
            searchEnabled: false,
            itemSelectText: '',
            fuseOptions: {
                threshold: 0,
                keys: ['label', 'value'],
            },
            searchFields: ['label', 'value'],
        });
    });

    Object.keys(selectorFilters).forEach((key) => {
        selectorFilters[key].addEventListener('change', function () {
            const selectedValue = this.value;
            urlManager.delete('pg', { silent: true });
            urlManager.set({ search_type: 'death-notices' }, { silent: true });
            if (selectedValue) {
                urlManager.set({ [key]: selectedValue });
            } else {
                urlManager.delete(key);
            }
            window.dispatchEvent(new CustomEvent('filterUpdate'));
        });
    });

    window.addEventListener('filterClearAll', () => {
        Object.keys(selectorFilters).forEach((key) => {
            urlManager.delete(key, { silent: true });
            choicesFilters[key].setChoiceByValue('');
        });
    });
})();
