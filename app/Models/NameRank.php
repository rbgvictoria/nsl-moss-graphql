<?php

namespace App\Models;

/**
 * @property integer $lockVersion
 * @property string $abbrev
 * @property boolean $deprecated
 * @property string $descriptionHtml
 * @property string $displayName
 * @property boolean $hasParent
 * @property boolean $italicize
 * @property boolean $major
 * @property string $name
 * @property string $rdfId
 * @property int $sortOrder
 * @property boolean $useVerbatimRank
 * @property boolean $visibleInName
 * 
 * @property NameGroup $nameGroup
 * @property NameRank $parentRank
 * @property Name[] $names
 */
class NameRank extends BaseModel
{

/**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'name_rank';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['name_group_id', 'parent_rank_id', 'lock_version', 'abbrev', 'deprecated', 'description_html', 'has_parent', 'italicize', 'major', 'name', 'rdf_id', 'sort_order', 'visible_in_name', 'use_verbatim_rank', 'display_name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function name_group()
    {
        return $this->belongsTo('App\Models\NameGroup');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent_rank()
    {
        return $this->belongsTo('App\Models\NameRank', 'parent_rank_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function names()
    {
        return $this->hasMany('App\Models\Name');
    }
}
