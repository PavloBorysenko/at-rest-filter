class PaginationManager {
    constructor(urlManager, config = {}) {
        this.urlManager = urlManager;
        this.config = {
            containerSelector:
                config.containerSelector || '.pagination-numbers',
            firstBtnSelector:
                config.firstBtnSelector || '.pagination-btn.first',
            prevBtnSelector: config.prevBtnSelector || '.pagination-btn.prev',
            nextBtnSelector: config.nextBtnSelector || '.pagination-btn.next',
            lastBtnSelector: config.lastBtnSelector || '.pagination-btn.last',
            pageParam: config.pageParam || 'pg',
        };
    }

    render(pagination) {
        const container = document.querySelector(this.config.containerSelector);
        const { current_page, pages } = pagination;

        if (!container) return;

        container.innerHTML = '';
        this.updateButtons(current_page, pages);

        const pageNumbers = this.generatePageNumbers(current_page, pages);

        pageNumbers.forEach((page) => {
            if (page === '...') {
                const dots = document.createElement('span');
                dots.className = 'pagination-dots';
                dots.textContent = '...';
                container.appendChild(dots);
            } else {
                const btn = this.createPageButton(page, current_page);
                container.appendChild(btn);
            }
        });
    }

    createPageButton(page, currentPage) {
        const btn = document.createElement('button');
        btn.className = 'pagination-number';
        btn.textContent = page;
        btn.dataset.page = page;

        if (page === currentPage) {
            btn.classList.add('active');
        }

        btn.addEventListener('click', () => {
            this.goToPage(page);
        });

        return btn;
    }

    generatePageNumbers(current, total) {
        const pages = [];

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
    }

    updateButtons(currentPage, totalPages) {
        const firstBtn = document.querySelector(this.config.firstBtnSelector);
        const prevBtn = document.querySelector(this.config.prevBtnSelector);
        const nextBtn = document.querySelector(this.config.nextBtnSelector);
        const lastBtn = document.querySelector(this.config.lastBtnSelector);

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
    }

    setupButtons() {
        document
            .querySelector(this.config.firstBtnSelector)
            ?.addEventListener('click', () => this.goToPage(1));

        document
            .querySelector(this.config.prevBtnSelector)
            ?.addEventListener('click', () => {
                const current = this.getCurrentPage();
                if (current > 1) {
                    this.goToPage(current - 1);
                }
            });

        document
            .querySelector(this.config.nextBtnSelector)
            ?.addEventListener('click', () => {
                const current = this.getCurrentPage();
                this.goToPage(current + 1);
            });

        document
            .querySelector(this.config.lastBtnSelector)
            ?.addEventListener('click', () => {
                const lastPageBtn = document.querySelector(
                    '.pagination-number:last-child'
                );
                if (lastPageBtn?.dataset.page) {
                    this.goToPage(parseInt(lastPageBtn.dataset.page));
                }
            });
    }

    goToPage(page) {
        this.urlManager.set({ [this.config.pageParam]: page });
    }

    getCurrentPage() {
        return parseInt(this.urlManager.get(this.config.pageParam) || '1');
    }
}
