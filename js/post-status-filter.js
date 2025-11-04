(function () {
    const statusSelect = document.getElementById('custom_status');
    if (!statusSelect) return;

    if (typeof URLManager === 'undefined') {
        console.error('URLManager is not loaded');
        return;
    }

    const urlManager = new URLManager();

    const statusChoices = new Choices(statusSelect, {
        shouldSort: false,
        searchEnabled: false,
        itemSelectText: '',
        fuseOptions: {
            threshold: 0,
            keys: ['label', 'value'],
        },
        searchFields: ['label', 'value'],
    });

    statusSelect.addEventListener('change', function () {
        const selectedStatus = this.value;

        urlManager.delete('pg', { silent: true });
        urlManager.set({ search_type: 'family-notices' }, { silent: true });
        if (selectedStatus) {
            urlManager.set({ status: selectedStatus });
        } else {
            urlManager.delete('status');
        }
        window.dispatchEvent(new CustomEvent('filterUpdate'));
    });

    window.addEventListener('filterClearAll', () => {
        urlManager.delete('status', { silent: true });
        statusChoices.setChoiceByValue('');
    });
})();
