document.addEventListener('DOMContentLoaded', function () {
    console.log('Death notice listing script loaded');
    console.log(
        'TemplateRenderer available:',
        typeof TemplateRenderer !== 'undefined'
    );

    if (typeof TemplateRenderer === 'undefined') {
        console.error(
            'TemplateRenderer is not loaded. Check if template-renderer.js is properly enqueued.'
        );
        return;
    }

    const renderer = new TemplateRenderer();
    console.log('TemplateRenderer initialized successfully');

    const urlManager = new URLManager({
        onUpdate: (params) => {
            fetchPosts();
        },
    });

    const paginationManager = new PaginationManager(urlManager);
    const perPageManager = new PerPageManager(urlManager);
    const sortManager = new SortManager(urlManager);

    const showLoading = () => {
        const overlay = document.querySelector('.at-rest-loading-overlay');
        if (overlay) {
            overlay.style.display = 'flex';
        }
    };

    const hideLoading = () => {
        const overlay = document.querySelector('.at-rest-loading-overlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
    };

    const fetchPosts = async () => {
        showLoading();

        const urlParams = new URLSearchParams(window.location.search);
        const postType = 'death-notices';
        const searchType = urlParams.get('search_type');
        const apiParams = new URLSearchParams({
            post_type: postType,
            page: urlManager.get('pg') || '1',
            per_page: urlManager.get('per-page') || '6',
            orderby: urlManager.get('orderby') || 'date',
            order: urlManager.get('order') || 'DESC',
        });

        if (searchType === postType) {
            urlParams.forEach((value, key) => {
                if (!apiParams.has(key) && value && value.trim()) {
                    const sanitizedValue = value.trim();
                    apiParams.set(key, sanitizedValue);
                }
            });
        }

        try {
            const response = await fetch(
                `/wp-json/at-rest/v1/posts/filter?${apiParams}`
            );
            const data = await response.json();

            console.log('Posts data:', data);
            console.log('Total posts:', data.pagination?.total);
            console.log('Posts:', data.posts);

            if (data.posts) {
                renderPosts(data.posts);
            }

            if (data.pagination) {
                paginationManager.render(data.pagination);
            }

            hideLoading();
            return data;
        } catch (error) {
            console.error('Error fetching posts:', error);
            hideLoading();
            return null;
        }
    };

    const renderPosts = (posts) => {
        const container = document.querySelector('.funeral-homes-table__body');
        const template = document.getElementById(
            'death-notice-list-template'
        )?.innerHTML;

        if (!container || !template) {
            console.error('Container or template not found');
            return;
        }

        if (posts.length === 0) {
            showEmptyState();
            return;
        }

        hideEmptyStates();
        container.innerHTML = posts
            .map((post) => renderer.render(template, post))
            .join('');

        initTippy();
    };

    const showEmptyState = () => {
        const table = document.querySelector('.family-notices-table');
        const pagination = document.querySelector(
            '.facetwp-pagination-controls'
        );
        const emptyState = document.querySelector('.is--empty-state');

        if (table) {
            table.style.display = 'none';
        }

        if (pagination) {
            pagination.style.display = 'none';
        }

        if (emptyState) {
            emptyState.style.display = 'block';
        }
    };

    const hideEmptyStates = () => {
        const table = document.querySelector('.family-notices-table');
        const pagination = document.querySelector(
            '.facetwp-pagination-controls'
        );
        const emptyStates = document.querySelectorAll(
            '.is--empty-state, .is--empty-state-empty'
        );

        if (table) {
            table.style.display = '';
        }

        if (pagination) {
            pagination.style.display = '';
        }

        emptyStates.forEach((state) => {
            state.style.display = 'none';
        });
    };

    const initTippy = () => {
        if (typeof tippy !== 'undefined') {
            tippy('[data-tippy-content]', {
                allowHTML: true,
                placement: 'top',
                theme: 'light',
            });
        }
    };

    paginationManager.setupButtons();
    perPageManager.init();
    sortManager.init();

    fetchPosts();
    initTippy();

    window.addEventListener('filterUpdate', () => {
        urlManager.delete('pg', { silent: true });
        fetchPosts();
    });
});
