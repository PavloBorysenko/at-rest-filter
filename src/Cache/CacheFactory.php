<?php

namespace Supernova\AtRestFilter\Cache;

/**
 * Factory for creating isolated cache instances per post type
 */
class CacheFactory {
    
    private bool $active;
    private string $basePrefix;
    
    public function __construct(string $basePrefix = 'at-rest-', bool $active = false) {
        $this->basePrefix = $basePrefix;
        $this->active = $active;
    }
    
    /**
     * Create isolated cache instance for specific post type
     * 
     * @param string $postType Post type slug
     * @return CacheInterface Isolated cache instance
     */
    public function create(string $postType): CacheInterface {
        $prefix = $this->basePrefix . sanitize_key($postType) . '-';
        return new TransientCache($prefix, $this->active);
    }
    
}

