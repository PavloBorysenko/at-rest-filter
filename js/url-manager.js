class URLManager {
    constructor(config = {}) {
        this.config = {
            onUpdate: config.onUpdate || null,
        };
    }

    get(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    getAll() {
        const urlParams = new URLSearchParams(window.location.search);
        return Object.fromEntries(urlParams);
    }

    set(params, options = {}) {
        const url = new URL(window.location);

        Object.entries(params).forEach(([key, value]) => {
            if (value !== null && value !== undefined && value !== '') {
                url.searchParams.set(key, value);
            } else {
                url.searchParams.delete(key);
            }
        });

        window.history.pushState({}, '', url);

        if (this.config.onUpdate && !options.silent) {
            this.config.onUpdate(params);
        }
    }

    delete(param, options = {}) {
        const url = new URL(window.location);
        url.searchParams.delete(param);
        window.history.pushState({}, '', url);

        if (this.config.onUpdate && !options.silent) {
            this.config.onUpdate({ [param]: null });
        }
    }

    clear(except = []) {
        const url = new URL(window.location);
        const urlParams = new URLSearchParams(window.location.search);

        Array.from(urlParams.keys()).forEach((key) => {
            if (!except.includes(key)) {
                url.searchParams.delete(key);
            }
        });

        window.history.pushState({}, '', url);

        if (this.config.onUpdate) {
            this.config.onUpdate({});
        }
    }
}
