<?php

namespace App\Models;

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
 * @property Namespace $namespace
 * @property InstanceNoteKey $instanceNoteKey
 */
class TaxonomicNameUsageNote extends InstanceNote
{
    /**
      *
     * @return App\Models\TaxonomicNameUsage
     */
    public function getTaxonomicNameUsageAttribute()
    {
        return \App\Models\TaxonomicNameUsage::find($this->instance_id);
    }

    public function getKindOfNoteAttribute()
    {
        return \App\Models\InstanceNoteKey::find($this->instance_note_key_id)->name;
    }
}
