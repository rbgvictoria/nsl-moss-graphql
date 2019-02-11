<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $author_id
 * @property integer $instance_id
 * @property integer $name_id
 * @property integer $reference_id
 * @property integer $lock_version
 * @property string $created_at
 * @property string $created_by
 * @property string $text
 * @property string $updated_at
 * @property string $updated_by
 * @property Reference $reference
 * @property Instance $instance
 * @property Author $author
 * @property Name $name
 */
class Comment extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'comment';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['author_id', 'instance_id', 'name_id', 'reference_id', 'lock_version', 'created_at', 'created_by', 'text', 'updated_at', 'updated_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reference()
    {
        return $this->belongsTo('App\Models\Reference');
    }

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
    public function author()
    {
        return $this->belongsTo('App\Models\Author');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function name()
    {
        return $this->belongsTo('App\Models\Name');
    }
}
