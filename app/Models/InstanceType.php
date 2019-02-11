<?php

namespace App\Models;

/**
 * @property integer $id
 * 
 * @property integer $lockVersion
 * @property boolean $citing
 * @property boolean $deprecated
 * @property string $descriptionHtml
 * @property boolean $doubtful
 * @property boolean $misapplied
 * @property string $name
 * @property boolean $nomenclatural
 * @property boolean $primaryInstance
 * @property boolean $pro_parte
 * @property boolean $protologue
 * @property string $rdfId
 * @property boolean $relationship
 * @property boolean $secondaryInstance
 * @property int $sort_order
 * @property boolean $standalone
 * @property boolean $synonym
 * @property boolean $taxonomic
 * @property boolean $unsourced
 * @property string $hasLabel
 * @property string $ofLabel
 * @property boolean $bidirectional
 * @property Instance[] $instances
 */
class InstanceType extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'instance_type';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['lock_version', 'citing', 'deprecated', 'description_html', 'doubtful', 'misapplied', 'name', 'nomenclatural', 'primary_instance', 'pro_parte', 'protologue', 'rdf_id', 'relationship', 'secondary_instance', 'sort_order', 'standalone', 'synonym', 'taxonomic', 'unsourced', 'has_label', 'of_label', 'bidirectional'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function instances()
    {
        return $this->hasMany('App\Models\Instance');
    }
}
