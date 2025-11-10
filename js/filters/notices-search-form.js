(function () {
    const form = document.querySelector('.filters__form');
    if (!form) return;

    if (typeof URLManager === 'undefined') {
        console.error('URLManager is not loaded');
        return;
    }

    const urlManager = new URLManager();

    const fields = Array.from(form.elements)
        .filter((el) => el.name && el.type !== 'button')
        .map((el) => el.name);

    const countySelect = document.getElementById('filter-county');
    const townSelect = document.getElementById('filter-town');
    const searchBtn = document.getElementById('search-button');
    const clearBtn = document.getElementById('clear-button');
    const countiesData = JSON.parse(
        document.getElementById('at-rest-county-data').textContent
    );
    const prefilteredData = JSON.parse(
        document.getElementById('at-rest-prefiltered-data').textContent
    );

    const datePickers = {};
    let countyChoices = null;
    let townChoices = null;

    const applyPrefilteredData = (filterData, urlManager) => {
        if (
            urlManager.get('search_type') ||
            urlManager.get('pg') ||
            urlManager.get('orderby') ||
            urlManager.get('per-page')
        ) {
            return;
        }
        urlManager.set(filterData, { silent: true });
    };

    const initCountyChoices = () => {
        if (typeof Choices === 'undefined' || !countySelect) return;

        countyChoices = new Choices(countySelect, {
            placeholder: true,
            placeholderValue: 'County',
            searchPlaceholderValue: 'Search',
            searchEnabled: true,
            shouldSort: true,
            itemSelectText: '',
            position: 'bottom',
            fuseOptions: {
                threshold: 0,
                keys: ['label', 'value'],
            },
            searchFields: ['label', 'value'],
        });

        countySelect.addEventListener('change', function () {
            const selectedCounty = this.value;
            handleCountyChange(selectedCounty);
        });
    };

    const initTownChoices = () => {
        if (typeof Choices === 'undefined' || !townSelect) return;

        townChoices = new Choices(townSelect, {
            placeholder: true,
            placeholderValue: 'Town',
            searchPlaceholderValue: 'Search',
            searchEnabled: true,
            shouldSort: true,
            itemSelectText: '',
            position: 'bottom',
            fuseOptions: {
                threshold: 0,
                keys: ['label', 'value'],
            },
            searchFields: ['label', 'value'],
        });

        townSelect.addEventListener('change', function () {
            const resetBtn = document.getElementById('reset_town');
            if (resetBtn) toggleResetBtn(townSelect, resetBtn);
            toggleClearAllBtn();
        });
    };

    const handleCountyChange = (selectedCounty) => {
        if (selectedCounty && countiesData[selectedCounty]) {
            const townOptions = [
                { value: '', label: 'Town' },
                ...countiesData[selectedCounty].map((town) => ({
                    value: town,
                    label: town,
                })),
            ];

            if (townChoices) {
                townChoices.setChoices(townOptions, 'value', 'label', true);
                townChoices.removeActiveItems();
                townChoices.enable();
                townSelect.disabled = false;
            }
        } else {
            if (townChoices) {
                townChoices.setChoices(
                    [{ value: '', label: 'Town' }],
                    'value',
                    'label',
                    true
                );
                townChoices.disable();
                townSelect.disabled = true;
            }
        }

        const resetTownBtn = document.getElementById('reset_town');
        if (resetTownBtn) resetTownBtn.style.display = 'none';

        const resetCountyBtn = document.getElementById('reset_county');
        if (resetCountyBtn) toggleResetBtn(countySelect, resetCountyBtn);
        toggleClearAllBtn();
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
            } else if (townChoices && input === townSelect) {
                const value = townChoices.getValue(true);
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
            } else if (fieldName === 'town' && townChoices) {
                const value = townChoices.getValue(true);
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
        if (townChoices) {
            townChoices.removeActiveItems();
            townChoices.setChoices(
                [{ value: '', label: 'Town' }],
                'value',
                'label',
                true
            );
            townChoices.disable();
        }
        if (townSelect) {
            townSelect.disabled = true;
        }

        Object.values(datePickers).forEach((picker) => picker?.clear());
        document
            .querySelectorAll('.form-user__reset-btn')
            .forEach((btn) => (btn.style.display = 'none'));
        toggleClearAllBtn();
    };

    const resetField = (fieldId) => {
        const field = document.getElementById(`filter-${fieldId}`);
        const resetBtn = document.getElementById(`reset_${fieldId}`);

        if (fieldId === 'county' && countyChoices) {
            countyChoices.removeActiveItems();
            if (townChoices) {
                townChoices.removeActiveItems();
                townChoices.hideDropdown();
                townChoices.disable();
            }
            townSelect.disabled = true;
            const resetTownBtn = document.getElementById('reset_town');
            if (resetTownBtn) resetTownBtn.style.display = 'none';
        } else if (fieldId === 'town' && townChoices) {
            townChoices.removeActiveItems();
        } else if (field.tagName === 'SELECT') {
            field.selectedIndex = 0;
            if (fieldId === 'county') {
                townSelect.selectedIndex = 0;
                townSelect.disabled = true;
                document.getElementById('reset_town').style.display = 'none';
            }
        } else {
            if (datePickers[fieldId]) {
                datePickers[fieldId].clear();
            } else {
                field.value = '';
            }
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

    const initDatePickers = () => {
        if (typeof flatpickr === 'undefined') return;

        const today = new Date();
        const fromInput = document.getElementById('filter-from');
        const toInput = document.getElementById('filter-to');

        if (!fromInput || !toInput) return;

        datePickers.from = flatpickr(fromInput, {
            dateFormat: 'd.m.Y',
            disableMobile: true,
            maxDate: today,
            onChange: () => {
                if (datePickers.from.selectedDates.length) {
                    datePickers.to.set(
                        'minDate',
                        datePickers.from.selectedDates[0]
                    );
                }
                const resetBtn = document.getElementById('reset_from');
                if (resetBtn) toggleResetBtn(fromInput, resetBtn);
                toggleClearAllBtn();
            },
        });

        datePickers.to = flatpickr(toInput, {
            dateFormat: 'd.m.Y',
            disableMobile: true,
            maxDate: today,
            onChange: () => {
                if (datePickers.to.selectedDates.length) {
                    datePickers.from.set(
                        'maxDate',
                        datePickers.to.selectedDates[0]
                    );
                }
                const resetBtn = document.getElementById('reset_to');
                if (resetBtn) toggleResetBtn(toInput, resetBtn);
                toggleClearAllBtn();
            },
        });
    };

    const restoreFormFromURL = () => {
        const urlParams = urlManager.getAll();

        fields.forEach((fieldName) => {
            const value = urlParams[fieldName];
            const field = document.getElementById(`filter-${fieldName}`);
            const resetBtn = document.getElementById(`reset_${fieldName}`);

            if (field && value) {
                if (fieldName === 'county' && countyChoices) {
                    countyChoices.setChoiceByValue(value);
                    handleCountyChange(value);

                    const townValue = urlParams['town'];
                    if (townValue && townChoices) {
                        setTimeout(() => {
                            townChoices.setChoiceByValue(townValue);
                            if (resetBtn) toggleResetBtn(field, resetBtn);
                        }, 100);
                    }
                } else if (fieldName === 'town' && townChoices) {
                    setTimeout(() => {
                        townChoices.setChoiceByValue(value);
                        if (resetBtn) toggleResetBtn(field, resetBtn);
                    }, 100);
                } else if (field.tagName === 'SELECT') {
                    field.value = value;
                    if (fieldName === 'county') {
                        const county = value;
                        townSelect.innerHTML =
                            '<option value="" disabled selected>Town</option>';

                        if (county && countiesData[county]) {
                            countiesData[county].forEach((town) => {
                                townSelect.innerHTML += `<option value="${town}">${town}</option>`;
                            });
                            townSelect.disabled = false;
                        }
                        const townValue = urlParams['town'];
                        if (townValue) {
                            townSelect.value = townValue;
                        }
                    }
                } else if (fieldName === 'from' || fieldName === 'to') {
                    setTimeout(() => {
                        if (datePickers[fieldName]) {
                            const datePicker = datePickers[fieldName];
                            const date = new Date(
                                value.split('.').reverse().join('-')
                            );
                            if (resetBtn) {
                                toggleResetBtn(field, resetBtn);
                            }
                            datePicker.setDate(date);
                        }
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
	
	applyPrefilteredData(prefilteredData, urlManager);

    initCountyChoices();
    initTownChoices();
    initDatePickers();

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
