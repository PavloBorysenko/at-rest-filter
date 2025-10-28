function renderMapLocations(posts) {
    toggleFavoriteMapLocation();
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
