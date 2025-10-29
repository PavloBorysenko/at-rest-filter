<?php

namespace Supernova\AtRestFilter\Filters;

class MapLocationFilter extends AbstractPostFilter {
    protected string $postType = 'map-location';
    protected function buildQueryArgs(array $params): array {

        $args = $this->getBaseQueryArgs($params);
        $args = $this->queryBuilder->getSearchArgs($args);

        return $args;
    }

    protected function formatPost(int $postId): array {
        return $this->dataPost->getPreparedData( $postId );
    }

}