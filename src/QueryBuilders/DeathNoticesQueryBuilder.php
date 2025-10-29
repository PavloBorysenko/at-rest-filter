<?php

namespace Supernova\AtRestFilter\QueryBuilders;

class DeathNoticesQueryBuilder implements QueryBuilder {
    private $params;
    public function __construct($params) {
        $this->params = $params;
    }
    public function getSearchArgs( array $args ): array {

        $params = $this->params ?? []; 
    
        
        $metaQuery = [];
    
        if ( ! empty( $params['firstname'] ) ) {
            $metaQuery[] = [
                'key'     => 'name',
                'value'   => $params['firstname'],
                'compare' => 'LIKE',
            ];
        }
    
        if ( ! empty( $params['surname'] ) ) {
            $metaQuery[] = [
                'key'     => 'surname',
                'value'   => $params['surname'],
                'compare' => 'LIKE',
            ];
        }
    
        if ( ! empty( $params['nee'] ) ) {
            $metaQuery[] = [
                'key'     => 'nee',
                'value'   => $params['nee'],
                'compare' => 'LIKE',
            ];
        }
    
        $metaQuery = $this->addSearchByCounty( $metaQuery );
        $metaQuery = $this->addSearchByTown( $metaQuery );

        $args = $this->addSearchByDate( $args );
    
        if ( isset( $args['meta_query'] ) && is_array( $args['meta_query'] ) ) {
            $relation = $args['meta_query']['relation'] ?? 'AND';
    
            $existingMeta = $args['meta_query'];
            unset( $existingMeta['relation'] );
    
            $args['meta_query'] = array_merge(
                ['relation' => $relation],
                $existingMeta,
                $metaQuery
            );
        } else {
            $args['meta_query'] = array_merge( ['relation' => 'AND'], $metaQuery );
        }
    
        return $args;
    }

    protected function addSearchByCounty( array $metaQuery ): array {
        if ( ! empty( $this->params['county'] ) ) {
            $countyQuery = [
                'relation' => 'OR',
                [
                    'key'     => 'select-county',
                    'value'   => $this->params['county'],
                    'compare' => '=',
                ],
            ];
    
            for ( $i = 0; $i < 3; $i++ ) {
                $countyQuery[] = [
                    'key'     => "additional_address_{$i}_additional_address_county",
                    'value'   => $this->params['county'],
                    'compare' => '=',
                ];
            }
    
            $metaQuery[] = $countyQuery;
        }

        return $metaQuery;
    }

    protected function addSearchByTown( array $metaQuery ): array {
        if ( ! empty( $this->params['town'] ) ) {
            $townQuery = [
                'relation' => 'OR',
                [
                    'key'     => 'select-town',
                    'value'   => $this->params['town'],
                    'compare' => '=',
                ],
            ];
    
            for ( $i = 0; $i < 3; $i++ ) {
                $townQuery[] = [
                    'key'     => "additional_address_{$i}_additional_address_town",
                    'value'   => $this->params['town'],
                    'compare' => '=',
                ];
            }
    
            $metaQuery[] = $townQuery;
        }   
        return $metaQuery;
    }
    protected function addSearchByDate( array $args ): array {
        if ( ! empty( $this->params['from'] ) ) {
            $args['date_query'][] = [
                'after'     => $this->convertDateToYmd( $this->params['from'] ),
                'inclusive' => true,
            ];
        }
    
        if ( ! empty( $this->params['to'] ) ) {
            $args['date_query'][] = [
                'before'    => $this->convertDateToYmd( $this->params['to'] ),
                'inclusive' => true,
            ];
        }
        return $args;
    }
    protected function convertDateToYmd( string $date ): string { 
		$parts = explode( '.', $date );
		if ( count( $parts ) === 3 ) {
			return sprintf( '%04d-%02d-%02d', $parts[2], $parts[1], $parts[0] );
		}
		return $date;
	} 
}