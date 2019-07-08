<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $name_group_id
 * @property integer $name_status_id
 * @property integer $lock_version
 * @property string $description_html
 * @property boolean $display
 * @property string $name
 * @property boolean $nom_illeg
 * @property boolean $nom_inval
 * @property string $rdf_id
 * @property boolean $deprecated
 * @property NameGroup $nameGroup
 * @property Name[] $names
 */
class NameStatus extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'name_status';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['name_group_id', 'name_status_id', 'lock_version', 'description_html', 'display', 'name', 'nom_illeg', 'nom_inval', 'rdf_id', 'deprecated'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nameGroup()
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
