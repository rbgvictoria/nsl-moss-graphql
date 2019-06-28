<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $author_id
 * @property integer $duplicate_of_id
 * @property integer $language_id
 * @property integer $namespace_id
 * @property integer $parent_id
 * @property integer $ref_author_role_id
 * @property integer $ref_type_id
 * 
 * @property integer $lockVersion
 * @property string $abbrevTitle
 * @property string $bhlUrl
 * @property string $citation
 * @property string $citationHtml
 * @property string $createdAt
 * @property string $createdBy
 * @property string $displayTitle
 * @property string $doi
 * @property string $edition
 * @property string $isbn
 * @property string $issn
 * @property string $notes
 * @property string $pages
 * @property string $publicationDate
 * @property boolean $published
 * @property string $publishedLocation
 * @property string $publisher
 * @property integer $sourceId
 * @property string $sourceIdString
 * @property string $sourceSystem
 * @property string $title
 * @property string $tl2
 * @property string $updatedAt
 * @property string $updatedBy
 * @property boolean $validRecord
 * @property string $verbatimAuthor
 * @property string $verbatimCitation
 * @property string $verbatimReference
 * @property string $volume
 * @property int $year
 * @property string $uri
 * 
 * @property Language $language
 * @property Reference $duplicateOf
 * @property RefAuthorRole $refAuthorRole
 * @property Reference $parent
 * @property RefType $refType
 * @property Author $author
 * @property Comment[] $comments
 * @property Instance[] $instances
 */
class Reference extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'reference';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['author_id', 'duplicate_of_id', 'language_id', 'namespace_id', 'parent_id', 'ref_author_role_id', 'ref_type_id', 'lock_version', 'abbrev_title', 'bhl_url', 'citation', 'citation_html', 'created_at', 'created_by', 'display_title', 'doi', 'edition', 'isbn', 'issn', 'notes', 'pages', 'publication_date', 'published', 'published_location', 'publisher', 'source_id', 'source_id_string', 'source_system', 'title', 'tl2', 'updated_at', 'updated_by', 'valid_record', 'verbatim_author', 'verbatim_citation', 'verbatim_reference', 'volume', 'year', 'uri'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function duplicate_of()
    {
        return $this->belongsTo('App\Models\Reference', 'duplicate_of_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ref_author_role()
    {
        return $this->belongsTo('App\Models\RefAuthorRole');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\Reference', 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ref_type()
    {
        return $this->belongsTo('App\Models\RefType');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo('App\Models\Author');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function instances()
    {
        return $this->hasMany('App\Models\Instance')->where('isStandalone', true);
    }

    public function getShortRefAttribute() {
        return $this->author->name . ' ' . $this->year;
    }
}
