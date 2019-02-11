<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $lock_version
 * @property string $description
 * @property boolean $is_doubtfully_naturalised
 * @property boolean $is_extinct
 * @property boolean $is_native
 * @property boolean $is_naturalised
 * @property string $region
 */
class Distribution extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'distribution';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['lock_version', 'description', 'is_doubtfully_naturalised', 'is_extinct', 'is_native', 'is_naturalised', 'region'];

}
