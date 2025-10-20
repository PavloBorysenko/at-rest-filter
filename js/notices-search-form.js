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
        document.querySelector('.at-rest-county-data').textContent
    );

    const datePickers = {};

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
        const hasValue =
            input.tagName === 'SELECT'
                ? input.value && input.selectedIndex > 0
                : input.value.trim();
        resetBtn.style.display = hasValue ? 'block' : 'none';
    };

    const toggleClearAllBtn = () => {
        const hasAnyValue = fields.some((fieldName) => {
            const field = document.getElementById(`filter-${fieldName}`);
            if (!field) return false;
            return field.tagName === 'SELECT'
                ? field.value && field.selectedIndex > 0
                : field.value.trim();
        });
        clearBtn.style.display = hasAnyValue ? 'block' : 'none';
    };

    const clearURLParams = () => {
        const params = { pg: null };
        fields.forEach((field) => {
            params[field] = null;
        });
        params.search_type = null;
        urlManager.set(params, { silent: true });
    };

    const clearForm = () => {
        Array.from(form.elements).forEach((el) => {
            if (el.tagName === 'INPUT' && el.type !== 'button') {
                el.value = '';
            } else if (el.tagName === 'SELECT') {
                el.selectedIndex = 0;
            }
        });

        countySelect.selectedIndex = 0;
        townSelect.selectedIndex = 0;
        townSelect.disabled = true;

        Object.values(datePickers).forEach((picker) => picker?.clear());
        document
            .querySelectorAll('.form-user__reset-btn')
            .forEach((btn) => (btn.style.display = 'none'));
        toggleClearAllBtn();
    };

    const resetField = (fieldId) => {
        const field = document.getElementById(`filter-${fieldId}`);
        const resetBtn = document.getElementById(`reset_${fieldId}`);

        if (field.tagName === 'SELECT') {
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
        const fromInput = document.getElementById('filter-date-from');
        const toInput = document.getElementById('filter-date-to');

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

    const initForm = () => {
        if (countySelect.value) {
            townSelect.disabled = false;
        }

        fields.forEach((fieldName) => {
            const field = document.getElementById(`filter-${fieldName}`);
            const resetBtn = document.getElementById(`reset_${fieldName}`);
            if (field && resetBtn) {
                toggleResetBtn(field, resetBtn);
            }
        });

        initDatePickers();
    };

    toggleClearAllBtn();
    initForm();

    countySelect.addEventListener('change', (e) => {
        const county = e.target.value;
        townSelect.innerHTML =
            '<option value="" disabled selected>Town</option>';

        if (county && countiesData[county]) {
            countiesData[county].forEach((town) => {
                townSelect.innerHTML += `<option value="${town}">${town}</option>`;
            });
            townSelect.disabled = false;
        } else {
            townSelect.disabled = true;
        }
        townSelect.selectedIndex = 0;
        document.getElementById('reset_town').style.display = 'none';
    });

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
})();
