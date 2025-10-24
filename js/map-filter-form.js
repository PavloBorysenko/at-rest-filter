(function () {
    const form = document.querySelector('.filters__form');
    if (!form) return;

    if (typeof URLManager === 'undefined') {
        console.error('URLManager is not loaded');
        return;
    }

    const searchBtn = document.getElementById('search-button');
    const clearBtn = document.getElementById('clear-button');
    const countySelect = document.getElementById('filter-county');
    const denominationSelect = document.getElementById('filter-category');
    const urlManager = new URLManager();

    const fields = Array.from(form.elements)
        .filter((el) => el.name && el.type !== 'button')
        .map((el) => el.name);

    console.log(fields);
    let countyChoices = null;
    let denominationChoices = null;

    const initCountyChoices = () => {
        if (typeof Choices === 'undefined' || !countySelect) return;
        countyChoices = new Choices(countySelect, {
            placeholder: true,
            placeholderValue: 'County',
            searchPlaceholderValue: 'Search',
            searchEnabled: true,
        });
    };

    const initDenominationChoices = () => {
        if (typeof Choices === 'undefined' || !denominationSelect) return;
        denominationChoices = new Choices(denominationSelect, {
            placeholder: true,
            placeholderValue: 'Denomination',
            searchPlaceholderValue: 'Search',
            searchEnabled: true,
        });
    };

    const updateURL = () => {
        const params = { pg: null };

        fields.forEach((field) => {
            params[field] = null;
        });
        params.search_type = null;

        const formData = new FormData(form);
        formData.forEach((value, key) => {
            if (value.trim()) {
                params[key] = value.trim();
            }
        });

        const searchType = form.dataset.searchType;
        if (searchType) {
            params.search_type = searchType;
        }

        urlManager.set(params, { silent: true });
        window.dispatchEvent(new CustomEvent('filterUpdate'));
    };

    const toggleResetBtn = (input, resetBtn) => {
        let hasValue = false;

        if (input.tagName === 'SELECT') {
            if (countyChoices && input === countySelect) {
                const value = countyChoices.getValue(true);
                hasValue = value && value.length > 0;
            } else if (denominationChoices && input === denominationSelect) {
                const value = denominationChoices.getValue(true);
                hasValue = value && value.length > 0;
            } else {
                hasValue = input.value && input.selectedIndex > 0;
            }
        } else {
            hasValue = input.value.trim();
        }

        resetBtn.style.display = hasValue ? 'block' : 'none';
    };
    const toggleClearAllBtn = () => {
        const hasAnyValue = fields.some((fieldName) => {
            const field = document.getElementById(`filter-${fieldName}`);
            if (!field) return false;

            if (fieldName === 'county' && countyChoices) {
                const value = countyChoices.getValue(true);
                return value && value.length > 0;
            } else if (fieldName === 'denomination' && denominationChoices) {
                const value = denominationChoices.getValue(true);
                return value && value.length > 0;
            } else if (field.tagName === 'SELECT') {
                return field.value && field.selectedIndex > 0;
            } else {
                return field.value.trim();
            }
        });

        const hasNoticeType = urlManager.get('search_type');

        clearBtn.style.display =
            hasAnyValue || hasNoticeType ? 'block' : 'none';
    };

    const clearURLParams = () => {
        const params = { pg: null };
        fields.forEach((field) => {
            params[field] = null;
        });
        params.search_type = null;
        urlManager.set(params);
    };

    const clearForm = () => {
        Array.from(form.elements).forEach((el) => {
            if (el.tagName === 'INPUT' && el.type !== 'button') {
                el.value = '';
            } else if (el.tagName === 'SELECT') {
                el.selectedIndex = 0;
            }
        });

        if (countyChoices) {
            countyChoices.removeActiveItems();
        }
        if (denominationChoices) {
            denominationChoices.removeActiveItems();
        }
        toggleClearAllBtn();
    };

    const resetField = (fieldId) => {
        const field = document.getElementById(`filter-${fieldId}`);
        const resetBtn = document.getElementById(`reset_${fieldId}`);

        if (fieldId === 'county' && countyChoices) {
            countyChoices.removeActiveItems();
        } else if (fieldId === 'denomination' && denominationChoices) {
            townChoices.removeActiveItems();
        } else if (field.tagName === 'SELECT') {
            field.selectedIndex = 0;
            if (fieldId === 'county') {
                townSelect.selectedIndex = 0;
                townSelect.disabled = true;
                document.getElementById('reset_town').style.display = 'none';
            }
        } else {
            field.value = '';
        }
        resetBtn.style.display = 'none';
        toggleClearAllBtn();
    };

    fields.forEach((fieldName) => {
        const field = document.getElementById(`filter-${fieldName}`);
        const resetBtn = document.getElementById(`reset_${fieldName}`);

        if (field && resetBtn) {
            const event = field.tagName === 'SELECT' ? 'change' : 'input';
            field.addEventListener(event, () => {
                toggleResetBtn(field, resetBtn);
                toggleClearAllBtn();
            });
            resetBtn.addEventListener('click', () => {
                resetField(fieldName);
                toggleClearAllBtn();
            });
        }
    });

    const restoreFormFromURL = () => {
        const urlParams = urlManager.getAll();

        fields.forEach((fieldName) => {
            const value = urlParams[fieldName];
            const field = document.getElementById(`filter-${fieldName}`);
            const resetBtn = document.getElementById(`reset_${fieldName}`);

            if (field && value) {
                if (fieldName === 'county' && countyChoices) {
                    countyChoices.setChoiceByValue(value);
                } else if (
                    fieldName === 'denomination' &&
                    denominationChoices
                ) {
                    setTimeout(() => {
                        townChoices.setChoiceByValue(value);
                        if (resetBtn) toggleResetBtn(field, resetBtn);
                    }, 100);
                } else {
                    field.value = value;
                    if (resetBtn) {
                        toggleResetBtn(field, resetBtn);
                    }
                }
            }
        });

        toggleClearAllBtn();
    };

    initCountyChoices();
    initDenominationChoices();

    setTimeout(() => {
        restoreFormFromURL();
        toggleClearAllBtn();
    }, 200);

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        updateURL();
    });

    searchBtn.addEventListener('click', updateURL);

    clearBtn.addEventListener('click', () => {
        clearForm();
        clearURLParams();
        window.dispatchEvent(
            new CustomEvent('filterClearAll', {
                detail: { source: form },
            })
        );
        window.dispatchEvent(new CustomEvent('filterUpdate'));
    });

    window.addEventListener('filterClearAll', (e) => {
        if (e.detail?.source === form) return;
        clearForm();
        clearURLParams();
    });

    window.addEventListener('filterUpdate', () => {
        toggleClearAllBtn();
    });
})();
