<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $lock_version
 * @property string $description_html
 * @property string $name
 * @property string $rdf_id
 * @property NameRank[] $nameRanks
 * @property NameStatus[] $nameStatuses
 * @property NameType[] $nameTypes
 */
class NameGroup extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'name_group';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['lock_version', 'description_html', 'name', 'rdf_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nameRanks()
    {
        return $this->hasMany('App\Models\NameRank');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nameStatuses()
    {
        return $this->hasMany('App\Models\NameStatus');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nameTypes()
    {
        return $this->hasMany('App\Models\NameType');
    }
}
