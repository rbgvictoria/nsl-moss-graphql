<?php

/*
 * Copyright 2019 Royal Botanic Gardens Victoria.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\TaxonomicNameUsageSearch;

use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

/**
 * Description of PlantSearch
 *
 * @author Niels.Klazenga <Niels.Klazenga at rbg.vic.gov.au>
 */
class TaxonomicNameUsageSearch {
    
    /**
     * 
     * @param Request $request
     * @return Builder
     */
    public static function apply($filter)
    {
        $builder = \App\Models\TaxonomicNameUsage
                ::whereHas('instance_type', function($builder) {
                    $builder->where('standalone', true)
                            ->orWhere('name', 'taxonomic synonym');
                });

        $query = static::applyDecoratorsFromRequest($filter, $builder);

        $query->join('name', 'instance.name_id', '=', 'name.id')
                ->join('reference', 'instance.reference_id', '=', 'reference.id')
                ->join('instance_type', 'instance.instance_type_id', '=', 'instance_type.id')
                ->join('name_status', 'name.name_status_id', '=', 'name_status.id')
                ->orderBy('name.full_name', 'asc')
                ->orderBy('instance_type.primary_instance', 'desc')
                ->orderBy('name_status.nom_inval', 'asc')
                ->orderBy('reference.year', 'asc')
                ->select('instance.*');
        
        return $query;
    }

    /**
     * 
     * @param Request $request
     * @param Builder $query
     * @return Builder
     */
    private static function applyDecoratorsFromRequest($filters, Builder $query)
    {
        if ($filters) {
            foreach ($filters as $filterName => $value) {

                $decorator = static::createFilterDecorator($filterName);

                if (static::isValidDecorator($decorator)) {
                    $query = $decorator::apply($query, $value);
                }

            }
        }
        return $query;
    }

    /**
     * 
     * @param string $name
     * @return string
     */
    private static function createFilterDecorator($name)
    {
        return __NAMESPACE__ . '\\Filters\\' . studly_case($name);
    }

    /**
     * 
     * @param string $decorator
     * @return boolean
     */
    private static function isValidDecorator($decorator)
    {
        return class_exists($decorator);
    }
    
}
