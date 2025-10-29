function renderMapLocations(posts) {
    toggleFavoriteMapLocation();
    initDeleteMapLocation();
}

window.addEventListener('postsRendered', renderMapLocations);

function toggleFavoriteMapLocation() {
    document
        .querySelectorAll('.favorite-toggle[data-type="map-location"]')
        .forEach((el) => {
            el.addEventListener('click', function () {
                const postId = this.dataset.postId;
                const row = this.closest('.funeral-homes-table__row');

                fetch('/wp-admin/admin-ajax.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'toggle_favorite_map_location',
                        post_id: postId,
                    }),
                })
                    .then((res) => res.json())
                    .then((data) => {
                        if (data.success) {
                            const isFav = data.data.is_favorite;
                            this.classList.toggle('is-active', isFav);

                            if (row) {
                                row.classList.toggle('is-favorite', isFav);
                            }
                        }
                    });
            });
        });
}

function initDeleteMapLocation() {
    let ajaxurl = '/wp-admin/admin-ajax.php';
    let deleteId = null;

    document
        .querySelectorAll(".favorite-delete[data-type='link']")
        .forEach((button) => {
            button.addEventListener('click', function () {
                deleteId = this.getAttribute('data-id');
                sessionStorage.setItem('confirmDeleteReady', '1');
            });
        });

    const confirmDeleteBtn = document.querySelector('#confirm-delete button');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function () {
            if (!sessionStorage.getItem('confirmDeleteReady') || !deleteId)
                return;

            const bodyData = new URLSearchParams({
                action: 'delete_map_location',
                post_id: deleteId,
            });

            fetch(ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: bodyData,
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        localStorage.setItem('map_location_deleted', '1');
                        location.reload();
                    } else {
                        alert(data.message || 'Something went wrong.');
                    }
                })
                .catch((err) => {
                    console.error('Fetch error', err);
                    alert('Server error. Check console.');
                });
        });
    }
}
