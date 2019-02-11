<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $version
 * @property string $data
 * @property string $description
 * @property string $file_name
 * @property string $mime_type
 * @property ResourceType[] $resourceTypes
 */
class MediaIcon extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'media';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['version', 'data', 'description', 'file_name', 'mime_type'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resourceTypes()
    {
        return $this->hasMany('App\Models\ResourceType', 'media_icon_id');
    }
}
