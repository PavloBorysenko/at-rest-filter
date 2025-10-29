<?php

namespace Supernova\AtRestFilter\QueryBuilders;

interface QueryBuilder {
    public function getSearchArgs( array $args ): array;
}