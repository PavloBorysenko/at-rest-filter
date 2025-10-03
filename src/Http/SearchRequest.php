<?php

namespace Supernova\AtRestFilter\Http;

class SearchRequest {
    
    private array $data = [];
    private array $fields;

    public function __construct(array $fields) {
        $this->fields = $this->normalizeFields($fields);
        $this->parseRequest();
    }

    private function normalizeFields(array $fields): array {
        $normalized = [];
        foreach ($fields as $key => $type) {
            if (is_int($key)) {
                $normalized[$type] = 'string';
            } else {
                $normalized[$key] = $type;
            }
        }
        return $normalized;
    }

    private function parseRequest(): void {
        foreach ($this->fields as $field => $type) {
            if (isset($_GET[$field])) {
                $value = $this->sanitize($_GET[$field], $type);
                if ($value !== null && $value !== '') {
                    $this->data[$field] = $value;
                }
            }
        }
    }

    private function sanitize($value, string $type) {
        switch ($type) {
            case 'int':
                return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
            case 'email':
                return sanitize_email($value);
            case 'date':
                $sanitized = sanitize_text_field($value);
                if (preg_match('/^\d{1,2}\.\d{1,2}\.\d{4}$/', $sanitized)) {
                    return $sanitized;
                }
                return null;
            case 'string':
            default:
                return sanitize_text_field($value);
        }
    }

    public function get(string $key, $default = null) {
        return $this->data[$key] ?? $default;
    }

    public function has(string $key): bool {
        return isset($this->data[$key]);
    }

    public function all(): array {
        return $this->data;
    }

    public function isEmpty(): bool {
        return empty($this->data);
    }
}

