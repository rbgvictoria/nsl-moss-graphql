<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $media_icon_id
 * @property integer $lock_version
 * @property string $css_icon
 * @property boolean $deprecated
 * @property string $description
 * @property boolean $display
 * @property string $name
 * @property string $rdf_id
 * @property MediaIcon $mediaIcon
 * @property Resource[] $resources
 */
class ResourceType extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'resource_type';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['media_icon_id', 'lock_version', 'css_icon', 'deprecated', 'description', 'display', 'name', 'rdf_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mediaIcon()
    {
        return $this->belongsTo('App\Models\MediaIcon', 'media_icon_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resources()
    {
        return $this->hasMany('App\Models\Resource');
    }
}
