function renderDeathNoticePhotoCondolences(detail) {
    atRestInitAllowCondolencesChange();
}

window.addEventListener('postsRendered', renderDeathNoticePhotoCondolences);

function atRestInitAllowCondolencesChange() {
    document.querySelectorAll('.is--toggler').forEach((input) => {
        input.addEventListener('change', function () {
            const row = this.closest('.funeral-homes-table__row');
            const postId = this.dataset.postId;
            const allow = this.checked ? 1 : 0;

            fetch(atRestData.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'toggle_condolences',
                    post_id: postId,
                    allow: allow,
                }),
            }).then(() => {
                const message = allow
                    ? 'Condolences are on'
                    : 'Condolences are off';
                if (typeof showGlobalAlert === 'function') {
                    showGlobalAlert(message);
                }

                const commentsCell = row.querySelector(
                    '.funeral-homes-table__cell:nth-child(4)'
                );
                if (!allow) {
                    if (commentsCell) {
                        commentsCell.innerHTML = `<div class=\"is--show-btn\">0</div>`;
                    }
                    return;
                }

                fetch(atRestData.ajaxurl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'get_comment_count',
                        post_id: postId,
                    }),
                })
                    .then((res) => res.json())
                    .then((data) => {
                        let count = data.success ? parseInt(data.data) : 0;
                        count = Math.min(count, 99);
                        if (commentsCell) {
                            commentsCell.innerHTML =
                                count > 0
                                    ? `<a href=\"/funeral-director/photo-and-condolence/condolences/?post_id=${postId}\" class=\"is--show-btn\">+${count}</a>`
                                    : `<div class=\"is--show-btn\">0</div>`;
                        }
                    });
            });
        });
    });
}
