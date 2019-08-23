<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;


class RelationshipUsage extends Instance {

    /**
     * Builder for RelationshipUsages
     *
     * @param [type] $roots
     * @param array $args
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function build($root, array $args): Builder
    {
        return \App\Models\RelationshipUsage
                ::whereHas('instance_type', function($builder) {
                    $builder->where('relationship', true);
                });
    }
}