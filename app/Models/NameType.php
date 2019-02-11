<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $name_category_id
 * @property integer $name_group_id
 * 
 * @property integer $lockVersion
 * @property boolean $autonym
 * @property string $connector
 * @property boolean $cultivar
 * @property boolean $deprecated
 * @property string $descriptionHtml
 * @property boolean $formula
 * @property boolean $hybrid
 * @property string $name
 * @property string $rdfId
 * @property boolean $scientific
 * @property int $sortOrder
 * 
 * @property NameCategory $nameCategory
 * @property NameGroup $nameGroup
 * @property Name[] $names
 */
class NameType extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'name_type';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['name_category_id', 'name_group_id', 'lock_version', 'autonym', 'connector', 'cultivar', 'deprecated', 'description_html', 'formula', 'hybrid', 'name', 'rdf_id', 'scientific', 'sort_order'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function name_category()
    {
        return $this->belongsTo('App\Models\NameCategory');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function name_group()
    {
        return $this->belongsTo('App\Models\NameGroup');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function names()
    {
        return $this->hasMany('App\Models\Name');
    }
}
