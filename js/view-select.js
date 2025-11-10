document.addEventListener('DOMContentLoaded', function () {
    const gridViewButton = document.querySelector(
        '.at-rest-view-select .view-grid__item.is--grid'
    );
    const listViewButton = document.querySelector(
        '.at-rest-view-select .view-grid__item.is--list'
    );
    function toggleViewMode(mode) {
        document.body.classList.toggle('is--grid-view', mode === 'grid');
        document.body.classList.toggle('is--list-view', mode === 'list');

        if (gridViewButton)
            gridViewButton.classList.toggle('active', mode === 'grid');
        if (listViewButton)
            listViewButton.classList.toggle('active', mode === 'list');

        localStorage.setItem('viewMode', mode);
    }

    if (gridViewButton && listViewButton) {
        gridViewButton.addEventListener('click', () => toggleViewMode('grid'));
        listViewButton.addEventListener('click', () => toggleViewMode('list'));

        const saved = localStorage.getItem('viewMode');
        toggleViewMode(saved || 'list');
    }
});
