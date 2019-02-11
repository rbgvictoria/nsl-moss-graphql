<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $previous_version_id
 * @property integer $tree_id
 * @property integer $lock_version
 * @property string $created_at
 * @property string $created_by
 * @property string $draft_name
 * @property string $log_entry
 * @property boolean $published
 * @property string $published_at
 * @property string $published_by
 * @property Tree $tree
 * @property TreeVersion $treeVersion
 * @property TreeVersionElement[] $treeVersionElements
 */
class TreeVersion extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tree_version';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['previous_version_id', 'tree_id', 'lock_version', 'created_at', 'created_by', 'draft_name', 'log_entry', 'published', 'published_at', 'published_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tree()
    {
        return $this->belongsTo('App\Models\Tree');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function treeVersion()
    {
        return $this->belongsTo('App\Models\TreeVersion', 'previous_version_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function treeVersionElements()
    {
        return $this->hasMany('App\Models\TreeVersionElement');
    }
}
