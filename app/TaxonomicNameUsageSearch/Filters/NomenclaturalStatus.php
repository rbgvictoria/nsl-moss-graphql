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

namespace App\TaxonomicNameUsageSearch\Filters;

use Illuminate\Database\Eloquent\Builder;

class NomenclaturalStatus implements Filter
{

    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param mixed $value
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value)
    {
        return $builder->whereHas('name.name_status', function ($query) use ($value) {
            switch($value) {
                case 'invalid':
                    $query->where('nom_inval', true);
                    break;
                case 'illegitimate':
                    $query->where('nom_illeg', true);
                    break;
                case 'superfluous':
                    $query->where('name', 'nom. superfl.');
                    break;
                default:
                    $query->where('name', $value);
            }
        });
    }
}