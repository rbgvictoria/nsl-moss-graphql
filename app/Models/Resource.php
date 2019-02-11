<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $site_id
 * @property integer $resource_type_id
 * @property integer $lock_version
 * @property string $created_at
 * @property string $created_by
 * @property string $path
 * @property string $updated_at
 * @property string $updated_by
 * @property ResourceType $resourceType
 * @property Site $site
 * @property Instance[] $instances
 */
class Resource extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'resource';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['site_id', 'resource_type_id', 'lock_version', 'created_at', 'created_by', 'path', 'updated_at', 'updated_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resourceType()
    {
        return $this->belongsTo('App\Models\ResourceType');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site()
    {
        return $this->belongsTo('App\Models\Site');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function instances()
    {
        return $this->belongsToMany('App\Models\Instance', 'instance_resources', 'instance_id', 'resource_id');
    }
}
