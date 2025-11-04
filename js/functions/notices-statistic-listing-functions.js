const addBtn = document.querySelector('.btn-icon.add');
if (typeof TemplateRenderer === 'undefined') {
    console.error(
        'TemplateRenderer is not loaded. Check if template-renderer.js is properly enqueued.'
    );
    // return;
}
const renderer = new TemplateRenderer();
const searchInput = document.getElementById('customStatisticSearch');
let countContainer = document.getElementById('count-text-container');

function renderCountText(event) {
    const detail = event.detail;
    const template = document.getElementById(
        `${event.detail.postType}-count-text-template`
    )?.innerHTML;
    let data = {};
    const searchQuery = searchInput.value;
    if (searchQuery && event.detail && template) {
        data = {
            count: detail.count,
            result_word: detail.count === 1 ? 'result' : 'results',
            search_query: searchQuery,
        };
        showCoutText(data, template);
    } else {
        hideCountText();
    }
}

window.addEventListener('postsRendered', renderCountText);

function showCoutText(data, template) {
    countContainer.innerHTML = renderer.render(template, data);
    if (addBtn) {
        addBtn.style.display = 'none';
    }
}

function hideCountText() {
    countContainer.textContent = '';
    if (addBtn) {
        addBtn.style.display = 'block';
    }
}
