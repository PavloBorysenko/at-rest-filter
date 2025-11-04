<?php

namespace Supernova\AtRestFilter\Data;

class FamilyNoticesCreateDataPost extends FamilyNoticesDataPost {
    protected $userId;
    protected $params;

    protected $is_admin = null;
    public function __construct($params) {
        $this->params = $params;
        $this->userId = $params['user_id'] ?? 0;
    }
    public function getPreparedData( $postId ): array {
        $data = parent::getPreparedData( $postId );
        $data['has_map'] = get_field('select_church_county', $postId)? true: false;
        $data['status'] = get_post_status( $postId );
        $data['status_text'] = ucfirst($data['status']);
        $data['edit_link'] = $this->getEditLink($postId);
        return $data;
    }
    private function getEditLink( $postId ): string {

        if ($this->isAdmin()) {
            $edit_url = add_query_arg('id', $postId, site_url('/funeral-director/family-notices/edit/'));
        } else {
            $edit_url = add_query_arg('id', $postId, site_url('/profile-family-notices/edit')); 
        }
        return $edit_url;
    }

    private function isAdmin(): bool {
        if ($this->is_admin !== null) {
            return $this->is_admin;
        }
        if (empty($this->userId) || !is_numeric($this->userId)) {
            return false;
        }
        $user = get_user_by('id', $this->userId);
        $admin_roles = ['administrator', 'editor', 'author', 'funeral_director'];
        $has_admin_role = array_intersect($admin_roles, (array) $user->roles);
        if (empty($has_admin_role)) {
            $this->is_admin = false;
        }
        $this->is_admin = true;
        return $this->is_admin;
    }

}