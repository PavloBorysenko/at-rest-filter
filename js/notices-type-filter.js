(function () {
    const typeSelect = document.getElementById('custom_type');
    if (!typeSelect) return;

    if (typeof URLManager === 'undefined') {
        console.error('URLManager is not loaded');
        return;
    }

    const urlManager = new URLManager();

    const typeChoices = new Choices(typeSelect, {
        shouldSort: false,
        searchEnabled: false,
        itemSelectText: '',
        fuseOptions: {
            threshold: 0,
            keys: ['label', 'value'],
        },
        searchFields: ['label', 'value'],
    });

    typeSelect.addEventListener('change', function () {
        const selectedType = this.value;

        urlManager.delete('pg', { silent: true });
        urlManager.set({ search_type: 'family-notices' }, { silent: true });
        if (selectedType) {
            urlManager.set({ notice_type: selectedType });
        } else {
            urlManager.delete('notice_type');
        }
        window.dispatchEvent(new CustomEvent('filterUpdate'));
    });

    window.addEventListener('filterClearAll', () => {
        urlManager.delete('notice_type', { silent: true });
        typeChoices.setChoiceByValue('');
    });
})();
