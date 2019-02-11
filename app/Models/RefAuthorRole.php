<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $lock_version
 * @property string $description_html
 * @property string $name
 * @property string $rdf_id
 * @property Reference[] $references
 */
class RefAuthorRole extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ref_author_role';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['lock_version', 'description_html', 'name', 'rdf_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function references()
    {
        return $this->hasMany('App\Models\Reference');
    }
}
