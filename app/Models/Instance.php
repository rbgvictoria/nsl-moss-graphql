<?php

namespace App\Models;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * @property integer $id
 * @property integer $cited_by_id
 * @property integer $cites_id
 * @property integer $instance_type_id
 * @property integer $name_id
 * @property integer $namespace_id
 * @property integer $parent_id
 * @property integer $reference_id
 * 
 * @property integer $lockVersion
 * @property string $bhlUrl
 * @property string $createdAt
 * @property string $createdBy
 * @property boolean $draft
 * @property string $nomenclaturalStatus
 * @property string $page
 * @property string $pageQualifier
 * @property integer $sourceId
 * @property string $sourceIdString
 * @property string $sourceSystem
 * @property string $updatedAt
 * @property string $updatedBy
 * @property boolean $validRecord
 * @property string $verbatimNameString
 * @property string $uri
 * @property string $cachedSynonymyHtml
 * 
 * @property Instance $cites
 * @property Name $name
 * @property Instance $parent
 * @property Reference $reference
 * @property InstanceType $instanceType
 * @property Instance $citedBy
 * @property InstanceNote[] $instanceNotes
 * @property Comment[] $comments
 * @property Resource[] $resources
 */
class Instance extends BaseModel
{
    use Eloquence, Mappable;
    
    protected $maps = [
        'isPrimaryInstance' => 'instance_type.primary_instance',
        'instanceTypeName' => 'instance_type.name',
        'isNomenclatural' => 'instance_type.nomenclatural',
        'isTaxonomic' => 'instance_type.taxonomic',
        'isMisapplied' => 'instance_type.misapplied',
        'isStandalone' => 'instance_type.stand_alone',
    ];
    
    protected $appends = [
        'isPrimaryInstance',
        'instanceTypeName',
        'isNomenclatural',
        'isTaxonomic',
        'isStandalone',
    ];
    
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'instance';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['cited_by_id', 'cites_id', 'instance_type_id', 'name_id', 'namespace_id', 'parent_id', 'reference_id', 'lock_version', 'bhl_url', 'created_at', 'created_by', 'draft', 'nomenclatural_status', 'page', 'page_qualifier', 'source_id', 'source_id_string', 'source_system', 'updated_at', 'updated_by', 'valid_record', 'verbatim_name_string', 'uri', 'cached_synonymy_html'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cites()
    {
        return $this->belongsTo('App\Models\Instance', 'cites_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function name()
    {
        return $this->belongsTo('App\Models\Name');
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\Instance', 'parent_id');
    }

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
    public function instance_type()
    {
        return $this->belongsTo('App\Models\InstanceType');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cited_by()
    {
        return $this->belongsTo('App\Models\Instance', 'cited_by_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function instance_notes()
    {
        return $this->hasMany('App\Models\InstanceNote');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function resources()
    {
        return $this->belongsToMany('App\Models\Resource', 'instance_resources', 'resource_id', 'instance_id');
    }
    
    /**
     * 
     * @return \App\Models\Instance
     */
    public function getBasionymAttribute()
    {
        if ($this->isPrimaryInstance) {
            $basionym = $this->hasMany('App\Models\Instance', 'cited_by_id', 'id')
                    ->whereIn('instanceTypeName', ['basionym', 'replaced synonym'])
                    ->first();
            if ($basionym) {
                return $basionym->name->primary_instance;
            }
        }
        return null;
    }
    
    protected function getRelationshipInstancesAttribute()
    {
        return Instance::where('cited_by_id', $this->id)->get();
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSynonymsAttribute()
    {
        return Instance::where('cited_by_id', $this->id)
                ->where(function($query) {
                    return $query->orWhere('isTaxonomic', true)
                            ->orWhere('isNomenclatural', true);
                })->get();
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMisapplicationsAttribute()
    {
        return Instance::where('cited_by_id', $this->id)
                ->where('isMisapplied', true)->get();
    }
    
    /**
     * 
     * @return \App\Models\Instance|null
     */
    public function getSynonymOfAttribute()
    {
        if ($this->isTaxonomic || $this->isNomenclatural) {
            return Instance::where('id', $this->cited_by_id)
                    ->first();
        }
        return null;
    }
    
    
    public function getMisappliedToAttribute()
    {
        if ($this->isMisapplied) {
            return Instance::where('id', $this->cited_by_id)
                    ->first();
        }
        return null;
    }
    
}
