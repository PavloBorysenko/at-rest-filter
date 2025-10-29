<?php

namespace Supernova\AtRestFilter\Data;

interface DataPost {
    public function getPreparedData( $postId ): array;
}