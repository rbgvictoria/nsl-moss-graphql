<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $current_tree_version_id
 * @property integer $default_draft_tree_version_id
 * @property integer $lock_version
 * @property boolean $accepted_tree
 * @property mixed $config
 * @property string $description_html
 * @property string $group_name
 * @property string $host_name
 * @property string $link_to_home_page
 * @property string $name
 * @property integer $reference_id
 * @property TreeVersion $defaultDraftTreeVersion
 * @property TreeVersion $currentTreeVersion
 * @property TreeVersion[] $treeVersions
 */
class Tree extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tree';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['current_tree_version_id', 'default_draft_tree_version_id', 'lock_version', 'accepted_tree', 'config', 'description_html', 'group_name', 'host_name', 'link_to_home_page', 'name', 'reference_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function defaultDraftTreeVersion()
    {
        return $this->belongsTo('App\Models\TreeVersion', 'default_draft_tree_version_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentTreeVersion()
    {
        return $this->belongsTo('App\Models\TreeVersion', 'current_tree_version_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function treeVersions()
    {
        return $this->hasMany('App\Models\TreeVersion');
    }
}
