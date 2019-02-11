<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $instance_id
 * @property integer $instance_note_key_id
 * @property integer $namespace_id
 * @property integer $lock_version
 * @property string $created_at
 * @property string $created_by
 * @property integer $source_id
 * @property string $source_id_string
 * @property string $source_system
 * @property string $updated_at
 * @property string $updated_by
 * @property string $value
 * @property Instance $instance
 * @property InstanceNoteKey $instanceNoteKey
 */
class InstanceNote extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'instance_note';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['instance_id', 'instance_note_key_id', 'namespace_id', 'lock_version', 'created_at', 'created_by', 'source_id', 'source_id_string', 'source_system', 'updated_at', 'updated_by', 'value'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instance()
    {
        return $this->belongsTo('App\Models\Instance');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instanceNoteKey()
    {
        return $this->belongsTo('App\Models\InstanceNoteKey');
    }
}
