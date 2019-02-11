<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $lock_version
 * @property string $description_html
 * @property string $name
 * @property string $rdf_id
 * @property int $sort_order
 * @property int $max_parents_allowed
 * @property int $min_parents_required
 * @property string $parent_1_help_text
 * @property string $parent_2_help_text
 * @property boolean $requires_family
 * @property boolean $requires_higher_ranked_parent
 * @property boolean $requires_name_element
 * @property boolean $takes_author_only
 * @property boolean $takes_authors
 * @property boolean $takes_cultivar_scoped_parent
 * @property boolean $takes_hybrid_scoped_parent
 * @property boolean $takes_name_element
 * @property boolean $takes_verbatim_rank
 * @property boolean $takes_rank
 * @property NameType[] $nameTypes
 */
class NameCategory extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'name_category';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['lock_version', 'description_html', 'name', 'rdf_id', 'sort_order', 'max_parents_allowed', 'min_parents_required', 'parent_1_help_text', 'parent_2_help_text', 'requires_family', 'requires_higher_ranked_parent', 'requires_name_element', 'takes_author_only', 'takes_authors', 'takes_cultivar_scoped_parent', 'takes_hybrid_scoped_parent', 'takes_name_element', 'takes_verbatim_rank', 'takes_rank'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nameTypes()
    {
        return $this->hasMany('App\Models\NameType');
    }
}
