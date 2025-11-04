class TemplateRenderer {
    constructor() {
        this.helpers = {
            image: (img, size = 'thumbnail') => {
                if (!img || !img.url) {
                    return '<div class="death-notice__placeholder"></div>';
                }
                const src = img[size] || img.url || '';
                const alt = img.alt || '';
                return `<img src="${src}" alt="${alt}" class="death-notice__image" />`;
            },

            icon: (icon) => {
                if (!icon) return '';
                const iconClass =
                    icon.type === 'arrangement'
                        ? 'is--arrange'
                        : `is--${icon.type}`;
                return `<span class="death-notice__icon ${iconClass}" data-tippy-content="${icon.tooltip}"></span>`;
            },

            date: (dateStr, format = 'short') => {
                if (!dateStr) return '';
                const date = new Date(dateStr);
                return format === 'long'
                    ? date.toLocaleDateString('en-GB', {
                          year: 'numeric',
                          month: 'long',
                          day: 'numeric',
                      })
                    : date.toLocaleDateString('en-GB');
            },

            join: (arr, separator = ', ') => {
                if (!Array.isArray(arr)) return '';
                return arr
                    .filter((item) => item && item.trim())
                    .join(separator);
            },

            empty: (value, placeholder = '—') => {
                return value && value.trim() ? value : placeholder;
            },

            selected: (current, expected) => {
                return current == expected ? 'selected="selected"' : '';
            },

            checked: (current, expected) => {
                if (expected === undefined) {
                    return current ? 'checked="checked"' : '';
                }
                return current == expected ? 'checked="checked"' : '';
            },

            disabled: (condition) => {
                return condition ? 'disabled="disabled"' : '';
            },

            attr: (name, value) => {
                return value ? `${name}="${value}"` : '';
            },
        };
    }

    evaluateCondition(condition, data) {
        let evaluated = condition;

        const stringLiterals = [];
        evaluated = evaluated.replace(
            /'([^']*)'|"([^"]*)"/g,
            (match, single, double) => {
                const index = stringLiterals.length;
                stringLiterals.push(single !== undefined ? single : double);
                return `__STR_${index}__`;
            }
        );

        evaluated = evaluated.replace(/([a-zA-Z_][\w.]*)/g, (match) => {
            if (
                ['true', 'false', 'null', 'undefined'].includes(match) ||
                match.startsWith('__STR_')
            ) {
                return match;
            }

            const value = this.getValue(data, match);
            if (typeof value === 'string') {
                return `"${value.replace(/"/g, '\\"')}"`;
            }

            return JSON.stringify(value);
        });

        evaluated = evaluated.replace(/__STR_(\d+)__/g, (match, index) => {
            return `"${stringLiterals[index].replace(/"/g, '\\"')}"`;
        });

        try {
            return new Function(`return ${evaluated}`)();
        } catch (e) {
            console.warn('Template condition evaluation error:', e, condition);
            return false;
        }
    }

    parseHelperArgs(argsString, data) {
        const args = [];
        const regex = /'([^']*)'|"([^"]*)"|(\S+)/g;
        let match;

        while ((match = regex.exec(argsString)) !== null) {
            if (match[1] !== undefined) {
                args.push(match[1]);
            } else if (match[2] !== undefined) {
                args.push(match[2]);
            } else {
                const value = this.getValue(data, match[3]);
                args.push(value);
            }
        }

        return args;
    }

    render(template, data) {
        let result = template;
        result = result.replace(
            /\{\{#if\s+([^}]+)\}\}([\s\S]*?)\{\{else\}\}([\s\S]*?)\{\{\/if\}\}/g,
            (match, condition, ifContent, elseContent) => {
                const isTrue = this.evaluateCondition(condition.trim(), data);
                return isTrue ? ifContent : elseContent;
            }
        );

        result = result.replace(
            /\{\{#if\s+([^}]+)\}\}([\s\S]*?)\{\{\/if\}\}/g,
            (match, condition, content) => {
                const isTrue = this.evaluateCondition(condition.trim(), data);
                return isTrue ? content : '';
            }
        );

        result = result.replace(
            /\{\{#each\s+([^}]+)\}\}([\s\S]*?)\{\{\/each\}\}/g,
            (match, arrayPath, content) => {
                const array = this.getValue(data, arrayPath.trim());
                if (!Array.isArray(array)) return '';

                return array
                    .map((item, index) => {
                        const itemData = {
                            ...data,
                            this: item,
                            '@index': index,
                        };
                        return this.render(content, itemData);
                    })
                    .join('');
            }
        );

        result = result.replace(
            /\{\{([^}#\/]+\?[^}]+:[^}]+)\}\}/g,
            (match, expression) => {
                const evaluated = this.evaluateCondition(
                    expression.trim(),
                    data
                );
                return evaluated !== null && evaluated !== undefined
                    ? evaluated
                    : '';
            }
        );

        result = result.replace(
            /\{\{(\w+)\s+([^}]+)\}\}/g,
            (match, helper, args) => {
                if (this.helpers[helper]) {
                    const argValues = this.parseHelperArgs(args, data);
                    return this.helpers[helper](...argValues);
                }
                return match;
            }
        );

        result = result.replace(/\{\{([^#\/][^}]*)\}\}/g, (match, key) => {
            return this.getValue(data, key.trim()) || '';
        });

        return result;
    }

    getValue(obj, path) {
        return path.split('.').reduce((current, prop) => {
            return current && current[prop] !== undefined
                ? current[prop]
                : null;
        }, obj);
    }
}
