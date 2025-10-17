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
    const perPageSelect = document.getElementById('per-page-select');
    const sortButtons = document.querySelectorAll('.sort-button');

    const fetchPosts = async () => {
        const urlParams = new URLSearchParams(window.location.search);

        const apiParams = new URLSearchParams({
            post_type: 'death-notices',
            page: urlParams.get('pg') || '1',
            per_page: urlParams.get('per-page') || '6',
            orderby: urlParams.get('orderby') || 'date',
            order: urlParams.get('order') || 'DESC',
        });

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
                renderPagination(data.pagination);
            }

            return data;
        } catch (error) {
            console.error('Error fetching posts:', error);
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

        // Initialize tippy for dynamically created elements
        initTippy();
    };

    const showEmptyState = () => {
        const container = document.querySelector('.funeral-homes-table__body');
        const emptyState = document.querySelector('.is--empty-state');

        if (container) {
            container.innerHTML = '';
        }

        if (emptyState) {
            emptyState.style.display = 'block';
        }
    };

    const hideEmptyStates = () => {
        const emptyStates = document.querySelectorAll(
            '.is--empty-state, .is--empty-state-empty'
        );
        emptyStates.forEach((state) => {
            state.style.display = 'none';
        });
    };

    const initTippy = () => {
        // Check if tippy is available
        if (typeof tippy !== 'undefined') {
            // Destroy existing tippy instances
            tippy('[data-tippy-content]', {
                allowHTML: true,
                placement: 'top',
                theme: 'light',
            });
        }
    };

    const renderPagination = (pagination) => {
        const paginationNumbers = document.querySelector('.pagination-numbers');
        const firstBtn = document.querySelector('.pagination-btn.first');
        const prevBtn = document.querySelector('.pagination-btn.prev');
        const nextBtn = document.querySelector('.pagination-btn.next');
        const lastBtn = document.querySelector('.pagination-btn.last');

        if (!paginationNumbers) return;

        const { current_page, pages } = pagination;

        paginationNumbers.innerHTML = '';

        if (pages <= 1) {
            if (firstBtn) firstBtn.disabled = true;
            if (prevBtn) prevBtn.disabled = true;
            if (nextBtn) nextBtn.disabled = true;
            if (lastBtn) lastBtn.disabled = true;
        } else {
            if (firstBtn) firstBtn.disabled = current_page === 1;
            if (prevBtn) prevBtn.disabled = current_page === 1;
            if (nextBtn) nextBtn.disabled = current_page === pages;
            if (lastBtn) lastBtn.disabled = current_page === pages;
        }

        const pageNumbers = generatePageNumbers(current_page, pages);

        pageNumbers.forEach((page) => {
            if (page === '...') {
                const dots = document.createElement('span');
                dots.className = 'pagination-dots';
                dots.textContent = '...';
                paginationNumbers.appendChild(dots);
            } else {
                const btn = document.createElement('button');
                btn.className = 'pagination-number';
                btn.textContent = page;
                btn.dataset.page = page;

                if (page === current_page) {
                    btn.classList.add('active');
                }

                btn.addEventListener('click', () => {
                    updateURL({ pg: page });
                });

                paginationNumbers.appendChild(btn);
            }
        });
    };

    const generatePageNumbers = (current, total) => {
        const pages = [];
        const maxVisible = 3;

        if (total <= 5) {
            for (let i = 1; i <= total; i++) {
                pages.push(i);
            }
        } else {
            pages.push(1);

            let start = Math.max(2, current - 1);
            let end = Math.min(total - 1, current + 1);

            if (current <= 3) {
                start = 2;
                end = Math.min(4, total - 1);
            } else if (current >= total - 2) {
                start = Math.max(2, total - 3);
                end = total - 1;
            }

            if (start > 2) {
                pages.push('...');
            }

            for (let i = start; i <= end; i++) {
                pages.push(i);
            }

            if (end < total - 1) {
                pages.push('...');
            }

            pages.push(total);
        }

        return pages;
    };

    const updateURL = (params) => {
        const url = new URL(window.location);
        Object.entries(params).forEach(([key, value]) => {
            value
                ? url.searchParams.set(key, value)
                : url.searchParams.delete(key);
        });
        window.history.pushState({}, '', url);

        if (params.pg) {
            updateActivePage(parseInt(params.pg));
        }

        window.dispatchEvent(new CustomEvent('filterUpdate'));
    };

    const updateActivePage = (newPage) => {
        document.querySelectorAll('.pagination-number').forEach((btn) => {
            btn.classList.remove('active');
        });

        document.querySelectorAll('.pagination-number').forEach((btn) => {
            if (parseInt(btn.dataset.page) === newPage) {
                btn.classList.add('active');
            }
        });

        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = parseInt(urlParams.get('pg') || '1');
        const lastPageBtn = document.querySelector(
            '.pagination-number:last-child'
        );
        const totalPages = lastPageBtn ? parseInt(lastPageBtn.dataset.page) : 1;

        const firstBtn = document.querySelector('.pagination-btn.first');
        const prevBtn = document.querySelector('.pagination-btn.prev');
        const nextBtn = document.querySelector('.pagination-btn.next');
        const lastBtn = document.querySelector('.pagination-btn.last');

        if (totalPages <= 1) {
            if (firstBtn) firstBtn.disabled = true;
            if (prevBtn) prevBtn.disabled = true;
            if (nextBtn) nextBtn.disabled = true;
            if (lastBtn) lastBtn.disabled = true;
        } else {
            if (firstBtn) firstBtn.disabled = currentPage === 1;
            if (prevBtn) prevBtn.disabled = currentPage === 1;
            if (nextBtn) nextBtn.disabled = currentPage === totalPages;
            if (lastBtn) lastBtn.disabled = currentPage === totalPages;
        }
    };
    const setupPaginationButtons = () => {
        document
            .querySelector('.pagination-btn.first')
            ?.addEventListener('click', () => {
                updateURL({ pg: 1 });
            });

        document
            .querySelector('.pagination-btn.prev')
            ?.addEventListener('click', () => {
                const urlParams = new URLSearchParams(window.location.search);
                const currentPage = parseInt(urlParams.get('pg') || '1');
                if (currentPage > 1) {
                    updateURL({ pg: currentPage - 1 });
                }
            });

        document
            .querySelector('.pagination-btn.next')
            ?.addEventListener('click', () => {
                const urlParams = new URLSearchParams(window.location.search);
                const currentPage = parseInt(urlParams.get('pg') || '1');
                updateURL({ pg: currentPage + 1 });
            });

        document
            .querySelector('.pagination-btn.last')
            ?.addEventListener('click', () => {
                const lastPageBtn = document.querySelector(
                    '.pagination-number:last-child'
                );
                if (lastPageBtn && lastPageBtn.dataset.page) {
                    updateURL({ pg: parseInt(lastPageBtn.dataset.page) });
                }
            });
    };

    fetchPosts();
    setupPaginationButtons();
    initTippy();

    window.addEventListener('filterUpdate', () => {
        fetchPosts();
    });

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
