function renderDeathNoticeCreate(detail) {
    atRestInitCheckboxes();
    atRestInitStatusChange();
    atRestInitDelete();
}

window.addEventListener('postsRendered', renderDeathNoticeCreate);

function atRestInitStatusChange() {
    const selects = document.querySelectorAll('.death-notice__status');
    const bulkStatusSelect = document.querySelector('.bulk-status-select');

    selects.forEach((select) => {
        select.addEventListener('change', function () {
            const postId = this.dataset.postId;
            const status = this.value;

            fetch(atRestData.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'update_death_notice_status',
                    post_id: postId,
                    status: status,
                }),
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        sessionStorage.setItem('custom_alert_status', '1');
                        location.reload();
                    } else {
                        alert('Error: ' + data.data);
                    }
                });
        });
    });

    if (sessionStorage.getItem('custom_alert_status') === '1') {
        sessionStorage.removeItem('custom_alert_status');
        setTimeout(() => {
            if (typeof showGlobalAlert === 'function') {
                showGlobalAlert('Status has been successfully changed');
            }
        }, 300);
    }

    bulkStatusSelect?.addEventListener('change', () => {
        const selected = [...getCheckboxes()]
            .filter((cb) => cb.checked)
            .map((cb) => cb.value);
        const newStatus = bulkStatusSelect.value;

        if (selected.length && newStatus) {
            const params = new URLSearchParams();
            params.append('action', 'bulk_update_death_notice_status');
            params.append('status', newStatus);
            selected.forEach((id) => params.append('post_ids[]', id));

            fetch(atRestData.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: params,
            })
                .then((res) => res.text())
                .then((text) => {
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            sessionStorage.setItem('custom_alert_status', '1');
                            location.reload();
                        } else {
                            alert('Error: ' + data.data);
                        }
                    } catch (err) {
                        console.error('Parse error:', text);
                        alert('Something went wrong. Check console.');
                    }
                });
        }
    });
}

function atRestInitCheckboxes() {
    const selectAll = document.getElementById('bulk-select-all');

    selectAll?.addEventListener('change', function () {
        getCheckboxes().forEach((cb) => (cb.checked = this.checked));
        updateBulkPanel();
    });

    document.addEventListener('change', function (e) {
        if (e.target.name === 'bulk_select[]') {
            updateBulkPanel();
            if (!e.target.checked && selectAll) {
                selectAll.checked = false;
            }
        }
    });
}

function atRestInitDelete() {
    let deleteMode = 'single';
    let deleteId = null;
    const bulkDeleteBtn = document.querySelector('.bulk-delete-btn');

    document
        .querySelectorAll(".favorite-delete[data-type='link']")
        .forEach((button) => {
            button.addEventListener('click', function () {
                deleteMode = 'single';
                deleteId = this.getAttribute('data-id');
                sessionStorage.setItem('confirmDeleteReady', '1');
            });
        });

    bulkDeleteBtn?.addEventListener('click', () => {
        const selected = [...getCheckboxes()]
            .filter((cb) => cb.checked)
            .map((cb) => cb.value);
        if (selected.length) {
            deleteMode = 'bulk';
            sessionStorage.setItem('confirmDeleteReady', '1');
            sessionStorage.setItem('bulkDeleteIds', JSON.stringify(selected));
        }
    });

    const confirmDeleteBtn = document.querySelector('#confirm-delete button');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function () {
            if (!sessionStorage.getItem('confirmDeleteReady')) return;

            const idsToDelete =
                deleteMode === 'bulk'
                    ? JSON.parse(
                          sessionStorage.getItem('bulkDeleteIds') || '[]'
                      )
                    : [deleteId];

            if (!idsToDelete.length) return;

            const params = new URLSearchParams();
            params.append('action', 'bulk_delete_death_notices');
            idsToDelete.forEach((id) => params.append('post_ids[]', id));

            fetch(atRestData.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: params,
            })
                .then((res) => res.text())
                .then((text) => {
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            sessionStorage.removeItem('confirmDeleteReady');
                            localStorage.setItem('death_notice_deleted', '1');
                            location.reload();
                        } else {
                            alert(data.message || 'Something went wrong.');
                        }
                    } catch (err) {
                        console.error('JSON parse error', text);
                        alert('Server error. Check console.');
                    }
                });
        });
    }
}

function getCheckboxes() {
    return document.querySelectorAll('input[name="bulk_select[]"]');
}

function updateBulkPanel() {
    const bulkPanel = document.querySelector('.bulk-actions');
    const anyChecked = [...getCheckboxes()].some((cb) => cb.checked);
    bulkPanel.style.display = anyChecked ? 'flex' : 'none';
}

window.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.get('saved') === '1') {
        showGlobalAlert('Death notice successfully added');
    }

    if (urlParams.get('draft') === '1') {
        showGlobalAlert('Death notice successfully saved as draft');
    }

    if (localStorage.getItem('death_notice_deleted') === '1') {
        showGlobalAlert('Death notice(s) successfully deleted');
        localStorage.removeItem('death_notice_deleted');
    }

    if (sessionStorage.getItem('custom_alert_status') === '1') {
        sessionStorage.removeItem('custom_alert_status');
        setTimeout(() => {
            if (typeof showGlobalAlert === 'function') {
                showGlobalAlert('Status has been successfully changed');
            }
        }, 300);
    }

    if (urlParams.get('saved') === '1' || urlParams.get('draft') === '1') {
        const url = new URL(window.location);
        url.searchParams.delete('saved');
        url.searchParams.delete('draft');
        window.history.replaceState({}, document.title, url.toString());
    }
});

document.addEventListener('facetwp-loaded', function () {
    if (window.location.pathname.includes('/funeral-director/death-notices')) {
        FWP.refresh = function () {};
    }
});
