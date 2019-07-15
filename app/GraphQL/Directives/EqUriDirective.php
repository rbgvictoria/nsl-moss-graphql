<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\ArgBuilderDirective;

class EqUriDirective extends BaseDirective implements ArgBuilderDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'eqUri';
    }

    /**
     * Apply a "WHERE = $value" clause.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $builder
     * @param  mixed  $value
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function handleBuilder($builder, $value)
    {   
        $test = strrpos($value, '/');
        if ($test !== false) {
            $value = (int) substr($value, strrpos($value, '/')+1);
        }
        return $builder->where(
            $this->directiveArgValue('key', $this->definitionNode->name->value),
            $value
        );
    }
}
