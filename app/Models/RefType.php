<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $parent_id
 * 
 * @property integer $lockVersion
 * @property string $descriptionHtml
 * @property string $name
 * @property boolean $parentOptional
 * @property string $rdfId
 * @property boolean $useParentDetails
 * 
 * @property RefType $parent
 * @property Reference[] $references
 */
class RefType extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ref_type';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['parent_id', 'lock_version', 'description_html', 'name', 'parent_optional', 'rdf_id', 'use_parent_details'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\RefType', 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function references()
    {
        return $this->hasMany('App\Models\Reference');
    }
}
