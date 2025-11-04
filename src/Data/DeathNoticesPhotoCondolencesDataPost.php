<?php

namespace Supernova\AtRestFilter\Data;

class DeathNoticesPhotoCondolencesDataPost extends DeathNoticesDataPost {
    protected $params;
    protected $userId;
    public function __construct($params) {
        $this->params = $params;
        $this->userId = $params['user_id'] ?? 0;
    }
    public function getPreparedData( $postId ): array {

        $allow_condolences = !!get_field('allow_condolences', $postId);
        $comments_count = $allow_condolences ?  $this->getCommentsCount( $postId ) : 0;

        $gallery_count = $this->getGalleryCount( $postId );

		return array(
			'id'                        => $postId,
			'title'                     => get_the_title( $postId ),
			'link'                      => get_permalink( $postId ),
			'publish_date'              => get_the_date( 'c', $postId ),
			'image'                     => $this->getImage( $postId ),
            'allow_condolences'         => (int)$allow_condolences,
            'comments_count'            => $comments_count,
            'gallery_count'             => $gallery_count,
            'display_comments'          => min(99, (int) $comments_count),
            'display_gallery'           => min(99, (int) $gallery_count),
            'condolences_link'          => $this->getCondolencesLink( $postId ),
            'gallery_link'              => $this->getGalleryLink( $postId ),
            'death_notice_link'         => $this->getDeathNoticeLink( $postId ),
		);
    }

    private function getCondolencesLink( $postId ): string {
        return "/funeral-director/photo-and-condolence/condolences/?post_id={$postId}";
    }
    private function getGalleryLink( $postId ): string {
        return "/funeral-director/photo-and-condolence/gallery/?post_id={$postId}";
    }
    private function getDeathNoticeLink( $postId ): string {
        return add_query_arg([
            'table' => 'true',
            'photo-condolences' => 'true'
        ], get_permalink($postId));
    }
    private function getGalleryCount( $postId ): int {
        $gallery = get_field('gallery', $postId);
        $pending_gallery = get_field('pending_gallery', $postId);
        return (is_array($gallery) ? count($gallery) : 0) + (is_array($pending_gallery) ? count($pending_gallery) : 0);
    }
    private function getCommentsCount( $postId ): int {
        return  get_comments(['post_id' => $postId, 'count' => true, 'status' => 'approve']) ;
    }
}