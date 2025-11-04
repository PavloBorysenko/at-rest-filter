<?php

namespace Supernova\AtRestFilter\Cache;

/**
 * Manages automatic cache clearing for post types
 */
class CacheManager {
    
    private CacheFactory $cacheFactory;
    private array $postTypes;
    
    public function __construct(CacheFactory $cacheFactory, array $postTypes) {
        $this->cacheFactory = $cacheFactory;
        $this->postTypes = $postTypes;
    }

    public function registerAutoClearing(): void {
        foreach ($this->postTypes as $postType) {
            // Clear on save
            add_action("save_post_{$postType}", function($post_id) use ($postType) {
                $this->clearForPostType($postType);
            }, 10, 1);
            
            // Clear on delete
            add_action("delete_post", function($post_id, $post) use ($postType) {
                if ($post->post_type === $postType) {
                    $this->clearForPostType($postType);
                }
            }, 10, 2);
        }
    }
    
    public function clearForPostType(string $postType): void {
        if (!in_array($postType, $this->postTypes)) {
            return;
        }
        
        $cache = $this->cacheFactory->create($postType);
        $cache->clear();
        
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("[AT Rest Filter] Cache cleared for: {$postType}");
        }
    }
    
    public function clearAll(): void {
        foreach ($this->postTypes as $postType) {
            $this->clearForPostType($postType);
        }
    }
}