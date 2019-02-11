<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $previous_element_id
 * @property integer $lock_version
 * @property string $display_html
 * @property boolean $excluded
 * @property integer $instance_id
 * @property string $instance_link
 * @property string $name_element
 * @property integer $name_id
 * @property string $name_link
 * @property mixed $profile
 * @property string $rank
 * @property string $simple_name
 * @property string $source_element_link
 * @property string $source_shard
 * @property mixed $synonyms
 * @property string $synonyms_html
 * @property string $updated_at
 * @property string $updated_by
 * @property TreeElement $previousElement
 * @property TreeVersionElement[] $treeVersionElements
 */
class TreeElement extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tree_element';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['previous_element_id', 'lock_version', 'display_html', 'excluded', 'instance_id', 'instance_link', 'name_element', 'name_id', 'name_link', 'profile', 'rank', 'simple_name', 'source_element_link', 'source_shard', 'synonyms', 'synonyms_html', 'updated_at', 'updated_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function previousElement()
    {
        return $this->belongsTo('App\Models\TreeElement', 'previous_element_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function treeVersionElements()
    {
        return $this->hasMany('App\Models\TreeVersionElement');
    }
}
