class SortManager {
    constructor(urlManager, config = {}) {
        this.urlManager = urlManager;
        this.config = {
            buttonSelector: config.buttonSelector || '.sort-button',
            orderByParam: config.orderByParam || 'orderby',
            orderParam: config.orderParam || 'order',
        };
    }

    init() {
        const buttons = document.querySelectorAll(this.config.buttonSelector);

        buttons.forEach((button) => {
            button.addEventListener('click', () => {
                this.handleSort(button, buttons);
            });
        });

        this.setInitialState();
    }

    handleSort(button, allButtons) {
        const sortBy = button.dataset.sort;
        const currentState = button.dataset.state || 'default';
        const nextState = currentState === 'desc' ? 'asc' : 'desc';

        allButtons.forEach((btn) => {
            btn.dataset.state = 'default';
            btn.classList.remove('is--asc', 'is--desc');
        });

        button.dataset.state = nextState;
        button.classList.add(`is--${nextState}`);

        this.urlManager.set({
            pg: null,
            [this.config.orderByParam]: sortBy,
            [this.config.orderParam]: nextState,
        });
    }

    setInitialState() {
        const currentOrderBy = this.urlManager.get(this.config.orderByParam);
        const currentOrder = this.urlManager.get(this.config.orderParam);

        if (!currentOrderBy || !currentOrder) return;

        const buttons = document.querySelectorAll(this.config.buttonSelector);
        buttons.forEach((button) => {
            if (button.dataset.sort === currentOrderBy) {
                button.dataset.state = currentOrder;
                button.classList.add(`is--${currentOrder}`);
            }
        });
    }

    reset() {
        const buttons = document.querySelectorAll(this.config.buttonSelector);
        buttons.forEach((btn) => {
            btn.dataset.state = 'default';
            btn.classList.remove('is--asc', 'is--desc');
        });
    }
}
