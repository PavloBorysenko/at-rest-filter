(function () {
    const form = document.querySelector('.at-rest-statistic-search-form');
    const searchInput = document.getElementById('customStatisticSearch');
    const clearButton = document.getElementById('clear-button');
    if (!form) return;
    const postType = form.dataset.searchType;

    if (typeof URLManager === 'undefined') {
        console.error('URLManager is not loaded');
        return;
    }

    const urlManager = new URLManager();

    const searchBtn = document.getElementById('search-button');

    searchBtn.addEventListener('click', () => {
        if (searchInput.value.trim()) {
            urlManager.delete('pg', { silent: true });
            urlManager.set({ _statistic_search: searchInput.value });
            urlManager.set({ search_type: postType });
            window.dispatchEvent(new CustomEvent('filterUpdate'));
        }
    });
    clearButton.addEventListener('click', (e) => {
        e.preventDefault();
        searchInput.value = '';
        urlManager.delete('_statistic_search', { silent: true });
        urlManager.delete('pg', { silent: true });
        urlManager.delete('search_type', { silent: true });
        urlManager.delete('orderby', { silent: true });
        urlManager.delete('order', { silent: true });

        window.dispatchEvent(
            new CustomEvent('filterClearAll', {
                detail: { source: form },
            })
        );
        window.dispatchEvent(new CustomEvent('filterUpdate'));
    });
})();
