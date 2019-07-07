<?php

namespace App\Models;


/**
 * @property integer $id
 * @property integer $authorId
 * @property integer $base_author_id
 * @property integer $duplicate_of_id
 * @property integer $ex_author_id
 * @property integer $ex_base_author_id
 * @property integer $name_rank_id
 * @property integer $name_status_id
 * @property integer $name_type_id
 * @property integer $namespace_id
 * @property integer $parent_id
 * @property integer $sanctioning_author_id
 * @property integer $second_parent_id
 * @property integer $family_id
 * 
 * @property integer $lockVersion
 * @property string $createdAt
 * @property string $createdBy
 * @property string $fullName
 * @property string $fullNameHtml
 * @property string $nameElement
 * @property boolean $orthVar
 * @property string $simpleName
 * @property string $simpleNameHtml
 * @property integer $sourceDupOfId
 * @property integer $sourceId
 * @property string $sourceIdString
 * @property string $sourceSystem
 * @property string $statusSummary
 * @property string $updatedAt
 * @property string $updatedBy
 * @property boolean $validRecord
 * @property string $verbatimRank
 * @property string $sortName
 * @property string $namePath
 * @property string $uri
 * @property boolean $changedCombination
 * @property int $publishedYear
 * @property mixed $apniJson
 *
 * @property Name $duplicateOf
 * @property NameStatus $nameStatus
 * @property Name $secondParent
 * @property Author $sanctioningAuthor
 * @property Author $author
 * @property NameType $nameType
 * @property Author $baseAuthor
 * @property Name $parent
 * @property Author $exBaseAuthor
 * @property Author $exAuthor
 * @property NameRank $nameRank
 * @property Name $family
 * @property Comment[] $comments
 * @property TaxonomicNameUsages[] $$taxonomicNameUsages
 * @property Tag[] $tags
 * @property TaxonomicName[] $children
 */
class TaxonomicName extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'name';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['author_id', 'base_author_id', 'duplicate_of_id', 'ex_author_id', 'ex_base_author_id', 'name_rank_id', 'name_status_id', 'name_type_id', 'namespace_id', 'parent_id', 'sanctioning_author_id', 'second_parent_id', 'family_id', 'lock_version', 'created_at', 'created_by', 'full_name', 'full_name_html', 'name_element', 'orth_var', 'simple_name', 'simple_name_html', 'source_dup_of_id', 'source_id', 'source_id_string', 'source_system', 'status_summary', 'updated_at', 'updated_by', 'valid_record', 'verbatim_rank', 'sort_name', 'name_path', 'uri', 'changed_combination', 'published_year', 'apni_json'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function duplicate_of()
    {
        return $this->belongsTo('App\Models\TaxonomicName', 'duplicate_of_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function name_status()
    {
        return $this->belongsTo('App\Models\NameStatus');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function second_parent()
    {
        return $this->belongsTo('App\Models\TaxonomicName', 'second_parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sanctioning_author()
    {
        return $this->belongsTo('App\Models\Author', 'sanctioning_author_id');
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
    public function name_type()
    {
        return $this->belongsTo('App\Models\NameType');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bas_author()
    {
        return $this->belongsTo('App\Models\Author', 'base_author_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\TaxonomicName', 'parent_id');
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany('\App\Models\TaxonomicName', 'parent_id', 'id')->orderBy('full_name');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bas_ex_author()
    {
        return $this->belongsTo('App\Models\Author', 'ex_base_author_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ex_author()
    {
        return $this->belongsTo('App\Models\Author', 'ex_author_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function name_rank()
    {
        return $this->belongsTo('App\Models\NameRank', 'name_rank_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function family()
    {
        return $this->belongsTo('App\Models\TaxonomicName', 'family_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    /**
     * Gets all taxonomicNameUsages for a TaxonomicName string
     * (replaces hasMany relationship, which I couldn't get to work)
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTaxonomicNameUsagesAttribute()
    {
        return \App\Models\TaxonomicNameUsage::where('name_id', '=', $this->id)->get();
    }

    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'name_tag_name', 'tag_id', 'name_id');
    }
    
    /**
     * 
     * @return \App\Model\Instance
     */
    public function getPrimaryInstanceAttribute() 
    {
        return $this->hasMany('\App\Models\Instance', 'name_id', 'id')
                ->where('isPrimaryInstance', '=', true)->first();
    }

    /**
     *
     * @return string
     */
    public function getGenericNameAttribute()
    {
        if ($this->nameRank->sortOrder > 120
                && $this->nameRank->sortOrder < 500) {
            return substr($this->simple_name, 0, strpos($this->simple_name, ' '));
        }
        return null;
    }

    /**
     *
     * @return string
     */
    public function getInfragenericNameAttribute()
    {
        if ($this->nameRank->sortOrder > 120 && $this->nameRank->sortOrder < 180) {
            return $this->parent->name_element;
        }
        return null;
    }

    /**
     *
     * @return string
     */
    public function getSpecificEpithetAttribute()
    {
        if ($this->nameRank->sortOrder > 190 && $this->nameRank->sortOrder < 500) {
            return $this->parent->name_element;
        }
        elseif ($this->nameRank->sortOrder === 190) {
            return $this->name_element;
        }
        return null;
    }

    /**
     *
     * @return string
     */
    public function getInfraspecificEpithetAttribute()
    {
        if ($this->nameRank->sortOrder > 190 && $this->nameRank->sortOrder < 500) {
            return $this->name_element;
        }
        return null;
    }

    /**
     *
     * @return string
     */
    public function getCultivarEpithetAttribute()
    {
        if ($this->nameType->cultivar === true) {
            return $this->name_element;
        }
        return null;
    }

    /**
     *
     * @return string
     */
    public function getAuthorshipAttribute() 
    {
        if ($this->author) {
            $authorship = '';
            if ($this->basAuthor) {
                $authorship .= '(';
                if ($this->exBasAuthor) {
                    $authorship .= $this->exBasAuthor->abbrev . ' ex ';
                }
                $authorship .= $this->basAuthor->abbrev . ') ';
            }
            if ($this->exAuthor) {
                $authorship .= $this->exAuthor->abbrev . ' ex ';
            }
            $authorship .= $this->author->abbrev;
            return $authorship;
        }
    }

    /**
     * Gets the nomenclatural code from the name_group table
     *
     * @return string
     */
    public function getNomenclaturalCodeAttribute()
    {
        return $this->name_type->name_group->name;
    }


    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $args
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByFullName($query) 
    {
        return $query->orderBy('full_name', 'asc');
    }

}
