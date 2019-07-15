<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

/**
 * 
 */
class Distribution extends BaseModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'tree_element_distribution_entries';

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * 
     * @return \App\Models\Location
     */
    public function getLocationAttribute()
    {
        return Location::join('dist_entry', 'dist_region.id', '=', 'dist_entry.region_id')
                ->join('tree_element_distribution_entries', 'dist_entry.id', '=', 'tree_element_distribution_entries.dist_entry_id')
                ->where('tree_element_distribution_entries.tree_element_id', $this->tree_element_id)
                ->where('tree_element_distribution_entries.dist_entry_id', $this->dist_entry_id)
                ->select('dist_region.*')
                ->first();          
    }

    /**
     * @return \String
     */
    public function getIdAttribute() 
    {
        $instanceId = DB::table('tree_element')
                ->where('id', $this->tree_element_id)
                ->value('instance_id');
        $locationId = $this->location->id;
        return $instanceId . '_' . $locationId;
    }

    /**
     * 
     *
     * @return \String
     */
    public function getOccurrenceStatusAttribute()
    {
        $status = DB::table('dist_status')
                ->join('dist_entry_dist_status', 'dist_status.id', '=', 'dist_entry_dist_status.dist_status_id')
                ->where('dist_entry_dist_status.dist_entry_status_id', $this->dist_entry_id)
                ->value('name');
        return ($status === 'uncertain') ? 'doubtful' : 'present';
    }
}
