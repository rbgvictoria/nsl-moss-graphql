<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $lock_version
 * @property string $name
 * @property Name[] $names
 */
class Tag extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'name_tag';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['lock_version', 'name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function names()
    {
        return $this->belongsToMany('App\Models\Name', 'name', 'name_id', 'tag_id');
    }
}
