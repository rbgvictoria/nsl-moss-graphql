<?php

namespace App\Models;

use App\TaxonomicNameUsageSearch\TaxonomicNameUsageSearch as Search;
use Illuminate\Database\Eloquent\Builder;

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
 * @property TaxonomicNameUsage $cites
 * @property TaxonomicName $$taxonomicName
 * @property TaxonomicNameUsage $parent
 * @property Reference $reference
 * @property InstanceType $instanceType
 * @property TaxonomicNameUsage $citedBy
 * @property InstanceNote[] $instanceNotes
 * @property Comment[] $comments
 * @property Resource[] $resources
 */
class TaxonomicNameUsage extends Instance
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cites()
    {
        return $this->belongsTo('App\Models\TaxonomicNameUsage', 'cites_id');
    }

    /**
     * @return \App\Models\TaxonomicName
     */
    public function getTaxonomicNameAttribute()
    {
        return \App\Models\TaxonomicName::where('id', $this->name_id)->first();
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\TaxonomicNameUsage', 'parent_id');
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
    public function taxonomic_name_usage_type()
    {
        return parent::instance_type();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cited_by()
    {
        return $this->belongsTo('App\Models\TaxonomicNameUsage', 'cited_by_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function protonym()
    {
        return $this->belongsTo('App\Models\TaxonomicNameUsage', 'protonym_id');
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
        return $this->belongsToMany('App\Models\Resource', 'instance_resources', 
                'resource_id', 'instance_id');
    }
    
    /**
     * 
     * @return \App\Models\TaxonomicNameUsage
     */
    public function getBasionymAttribute()
    {
        if ($this->instance_type->primary_instance) {
            $basionymTypes = \App\Models\InstanceType
                    ::whereIn('name', ['basionym', 'replaced_synonym'])
                    ->pluck('id');
            $basionymInstance = TaxonomicNameUsage::where('cited_by_id', $this->id)
                    ->whereIn('instance_type_id', $basionymTypes)
                    ->first();
            if ($basionymInstance) {
                return $basionymInstance->cites;
            }
        }
        return null;
    }

    /**
     * Undocumented function
     *
     * @return \App\Models\TaxonomicNameUsage
     */
    public function getPrimaryNameUsageAttribute() 
    {
        return $this->taxonomic_name->taxonomic_name_usages->filter(function ($usage) {
            return $usage->instance_type->primary_instance;
        })->first();
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTaxonomicNameUsagesAttribute()
    {
        if ($this->id === $this->protonym->id) {
            return \App\Models\TaxonomicNameUsage::where('protonym_id', $this->id)->get();
        }
    }

    protected function getRelationshipInstancesAttribute()
    {
        return TaxonomicNameUsage::where('cited_by_id', $this->id)->get();
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getHeterotypicSynonymsAttribute()
    {
        $synonymTypes = \App\Models\InstanceType::where('name', 'taxonomic synonym')
                ->pluck('id');
        return TaxonomicNameUsage::where('cited_by_id', $this->id)
                ->whereIn('instance_type_id', $synonymTypes)->get();
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getHomotypicSynonymsAttribute()
    {
        $protonym = $this->protonym;
        if ($this->instance_type->primary_instance) {
            return \App\Models\TaxonomicNameUsage
                ::where('protonym_id', $this->protonym->id)
                ->get()
                ->filter(function($tnu) {
                    return $tnu->instance_type->primary_instance;
                });
        }
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMisapplicationsAttribute()
    {
        return TaxonomicNameUsage::where('cited_by_id', $this->id)
                ->where('isMisapplied', true)->get();
    }
    
    /**
     * 
     * @return \App\Models\TaxonomicNameUsage|null
     */
    public function getAcceptedNameUsageAttribute()
    {
        if ($this->instance_type->name === 'taxonomic synonym') {
            return TaxonomicNameUsage::where('id', $this->cited_by_id)
                    ->first();
        }
        return null;
    }
    
    
    public function getMisappliedToAttribute()
    {
        if ($this->isMisapplied) {
            return TaxonomicNameUsage::where('id', $this->cited_by_id)
                    ->first();
        }
        return null;
    }

    public function getTaxonomicNameUsageLabelAttribute() 
    {
        return $this->taxonomic_name->full_name . ' sec. ' . $this->reference->short_ref;
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNotesAttribute()
    {
        return \App\Models\TaxonomicNameUsageNote
                ::where('instance_id', $this->id)->get();
    }

    /**
     * Builder for taxonomicNameUsages Query
     *
     * @param [type] $roots
     * @param array $args
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function build($root, array $args): Builder
    {
        $filter = isset($args['filter']) ? $args['filter'] : null;
        return Search::apply($filter);     
    }

}
