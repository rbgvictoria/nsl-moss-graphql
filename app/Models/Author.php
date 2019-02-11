<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $duplicate_of_id
 * @property integer $namespace_id
 * 
 * @property integer $lockVersion
 * @property string $abbrev
 * @property string $createdAt
 * @property string $createdBy
 * @property string $dateRange
 * @property string $fullName
 * @property string $ipniId
 * @property string $name
 * @property string $notes
 * @property integer $sourceId
 * @property string $sourceIdString
 * @property string $sourceSystem
 * @property string $updatedAt
 * @property string $updatedBy
 * @property boolean $validRecord
 * @property string $uri
 * @property Author $duplicateOf
 * @property Comment[] $comments
 */
class Author extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'author';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['duplicate_of_id', 'namespace_id', 'lock_version', 'abbrev', 'created_at', 'created_by', 'date_range', 'full_name', 'ipni_id', 'name', 'notes', 'source_id', 'source_id_string', 'source_system', 'updated_at', 'updated_by', 'valid_record', 'uri'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function duplicate_of()
    {
        return $this->belongsTo('App\Models\Author', 'duplicate_of_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

}
