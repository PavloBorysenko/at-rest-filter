(function () {
    const publishSelect = document.getElementById('custom_pub');
    if (!publishSelect) return;

    if (typeof URLManager === 'undefined') {
        console.error('URLManager is not loaded');
        return;
    }

    const urlManager = new URLManager();

    const publishChoices = new Choices(publishSelect, {
        shouldSort: false,
        searchEnabled: false,
        itemSelectText: '',
        fuseOptions: {
            threshold: 0,
            keys: ['label', 'value'],
        },
        searchFields: ['label', 'value'],
    });

    publishSelect.addEventListener('change', function () {
        const selectedPublish = this.value;

        urlManager.delete('pg', { silent: true });
        urlManager.set({ search_type: 'map-location' }, { silent: true });
        if (selectedPublish) {
            urlManager.set({ pub: selectedPublish });
        } else {
            urlManager.delete('pub');
        }
        window.dispatchEvent(new CustomEvent('filterUpdate'));
    });

    window.addEventListener('filterClearAll', () => {
        urlManager.delete('pub', { silent: true });
        publishChoices.setChoiceByValue('');
    });
})();
