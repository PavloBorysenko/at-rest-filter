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

                fetch(atRestData.ajaxurl, {
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
                            
                            if (typeof showGlobalAlert === 'function') {
                                showGlobalAlert(isFav ? 'Added to favorites' : 'Removed from favorites');
                            }
                        }
                    });
            });
        });
}

function initDeleteMapLocation() {
    let ajaxurl = atRestData.ajaxurl;
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

            fetch(atRestData.ajaxurl, {
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

function viewAlertsMapLocation(){
	document.addEventListener('DOMContentLoaded', function(){
		const urlParams = new URLSearchParams(window.location.search);
	
		if (localStorage.getItem("map_location_deleted") === "1") {
			if (typeof showGlobalAlert === "function") {
				showGlobalAlert("Map / Location successfully deleted");
			}
			localStorage.removeItem("map_location_deleted");
		}

		if (urlParams.get("draft") === "1") {
			showGlobalAlert("Map / Location successfully saved");
		}

		if (urlParams.get("publish") === "1") {
			showGlobalAlert("Map / Location has been successfully published");
		}

		if (sessionStorage.getItem("custom_alert_status") === "1") {
			sessionStorage.removeItem("custom_alert_status");
			setTimeout(() => {
			  if (typeof showGlobalAlert === 'function') {
				showGlobalAlert('Status has been successfully changed');
			  }
			}, 300);
		}

		if (urlParams.get("saved") === "1" || urlParams.get("draft") === "1" || urlParams.get("publish") === "1") {
			const url = new URL(window.location);
			url.searchParams.delete("saved");
			url.searchParams.delete("draft");
			url.searchParams.delete("publish");
			window.history.replaceState({}, document.title, url.toString());
		}
	});
}

viewAlertsMapLocation();
