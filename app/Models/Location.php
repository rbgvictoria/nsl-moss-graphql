<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @property integer $id
 * @property integer $lock_version
 * @property boolean $deprecated
 * @property string $description_html
 * @property string $def_link
 * @property string $name
 * @property int $sort_order
 * @property DistEntry[] $distEntries
 */
class Location extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'dist_region';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['lock_version', 'deprecated', 'description_html', 'def_link', 'name', 'sort_order'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function distEntries()
    {
        return $this->hasMany('App\Models\DistEntry', 'region_id');
    }
}
