document.addEventListener('DOMContentLoaded', function () {
    const perPageSelect = document.getElementById('per-page-select');
    const sortButtons = document.querySelectorAll('.sort-button');

    const updateURL = (params) => {
        const url = new URL(window.location);
        Object.entries(params).forEach(([key, value]) => {
            value
                ? url.searchParams.set(key, value)
                : url.searchParams.delete(key);
        });
        window.history.pushState({}, '', url);
        window.dispatchEvent(new CustomEvent('filterUpdate'));
    };

    if (perPageSelect && typeof Choices !== 'undefined') {
        const urlParams = new URLSearchParams(window.location.search);
        const currentPerPage = urlParams.get('per-page') || '6';

        window.choicesPerPage = new Choices(perPageSelect, {
            shouldSort: false,
            searchEnabled: false,
            itemSelectText: '',
            removeItemButton: false,
        });

        window.choicesPerPage.setChoiceByValue(currentPerPage);

        perPageSelect.addEventListener('change', function () {
            updateURL({ 'per-page': window.choicesPerPage.getValue(true) });
        });
    }

    sortButtons.forEach((button) => {
        button.addEventListener('click', function () {
            const sortBy = this.dataset.sort;
            const currentState = this.dataset.state || 'default';
            const nextState = currentState === 'desc' ? 'asc' : 'desc';

            sortButtons.forEach((btn) => {
                btn.dataset.state = 'default';
                btn.classList.remove('is--asc', 'is--desc');
            });

            this.dataset.state = nextState;
            this.classList.add(`is--${nextState}`);

            updateURL({
                orderby: sortBy,
                order: nextState,
            });
        });
    });
});
