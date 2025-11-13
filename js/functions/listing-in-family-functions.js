window.addEventListener('filterUpdate', showListingInFamily);

function showListingInFamily() {
    const listingInFamily = document.querySelector(
        '.at-rest-post-listing[data-type="family"]'
    );
    if (listingInFamily) {
        listingInFamily.style.display = 'block';
    }
}
