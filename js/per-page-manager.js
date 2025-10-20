class PerPageManager {
    constructor(urlManager, config = {}) {
        this.urlManager = urlManager;
        this.config = {
            selectSelector: config.selectSelector || '#per-page-select',
            perPageParam: config.perPageParam || 'per-page',
            defaultValue: config.defaultValue || '6',
        };
        this.choicesInstance = null;
    }

    init() {
        const select = document.querySelector(this.config.selectSelector);

        if (!select || typeof Choices === 'undefined') {
            return;
        }

        const currentValue =
            this.urlManager.get(this.config.perPageParam) ||
            this.config.defaultValue;

        this.choicesInstance = new Choices(select, {
            shouldSort: false,
            searchEnabled: false,
            itemSelectText: '',
            removeItemButton: false,
        });

        this.choicesInstance.setChoiceByValue(currentValue);

        select.addEventListener('change', () => {
            const value = this.choicesInstance.getValue(true);
            this.urlManager.set({
                pg: null,
                [this.config.perPageParam]: value,
            });
        });
    }

    destroy() {
        if (this.choicesInstance) {
            this.choicesInstance.destroy();
        }
    }
}
