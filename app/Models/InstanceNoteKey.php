<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $lock_version
 * @property boolean $deprecated
 * @property string $description_html
 * @property string $name
 * @property string $rdf_id
 * @property int $sort_order
 * @property InstanceNote[] $instanceNotes
 */
class InstanceNoteKey extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'instance_note_key';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['lock_version', 'deprecated', 'description_html', 'name', 'rdf_id', 'sort_order'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function instanceNotes()
    {
        return $this->hasMany('App\Models\InstanceNote');
    }
}
