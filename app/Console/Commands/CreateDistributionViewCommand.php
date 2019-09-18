<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDistributionViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tnu:create:distribution-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create tnu_distribution view.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sql = <<<EOT
CREATE OR REPLACE VIEW tnu_distribution AS
SELECT te.id::text, t.id as taxon_id, 'ISO:AU' as locality_id, 'Australia' as locality, 'AU' as country_code, t.occurrence_status, null as establishment_means
FROM tnu_taxonomic_name_usages t
JOIN tnu_taxonomic_names n ON t.taxonomic_name=n.id
JOIN (
    SELECT te.id, te.instance_id
    FROM tree_element te
    JOIN tree_version_element tve ON te.id=tve.tree_element_id
    WHERE tve.tree_version_id = (
    SELECT tree_version.id
    FROM tree_version
    WHERE tree_version.published = true AND tree_version.published_at = ( 
        SELECT max(published_at) AS max
        FROM tree_version
        WHERE published = true
    )
    )
) as te ON t.id=te.instance_id
WHERE n.rank IN ('species', 'subspecies', 'variety', 'form') AND t.taxonomic_status='accepted'
UNION
SELECT 
    te.id||'_'||r.id as id, 
    t.id as taxon_id,
    CASE r.name 
    WHEN 'WA' THEN 'ISO:AU-WA'
    WHEN 'CoI' THEN 'ISO:CC'
    WHEN 'ChI' THEN 'ISO:CX'
    WHEN 'NT' THEN 'ISO:AU-NT'
    WHEN 'SA' THEN 'ISO:AU-SA'
    WHEN 'Qld' THEN 'ISO:AU-QLD'
    WHEN 'NSW' THEN 'ISO:AU-NSW'
    WHEN 'LHI' THEN 'TDWG:NFK-LH'
    WHEN 'NI' THEN 'ISO:NF'
    WHEN 'ACT' THEN 'ISO:ACT'
    WHEN 'Vic' THEN 'ISO:AU-VIC'
    WHEN 'Tas' THEN 'ISO:AU-TAS'
    WHEN 'HI' THEN 'ISO:HM'
    WHEN 'MI' THEN 'TDWG:MAQ'
    WHEN 'AR' THEN 'TDWG:WAU-AC'
    END as locality_id,
    CASE r.name 
    WHEN 'WA' THEN 'Western Australia'
    WHEN 'CoI' THEN 'Cocos (Keeling) Islands'
    WHEN 'ChI' THEN 'Christmas Island'
    WHEN 'NT' THEN 'Northern Territory'
    WHEN 'SA' THEN 'South Australia'
    WHEN 'Qld' THEN 'Queensland'
    WHEN 'NSW' THEN 'New South Wales'
    WHEN 'LHI' THEN 'Lord Howe Island'
    WHEN 'NI' THEN 'Norfolk Island'
    WHEN 'ACT' THEN 'Australian Capital Territory'
    WHEN 'Vic' THEN 'Victoria'
    WHEN 'Tas' THEN 'Tasmania'
    WHEN 'HI' THEN 'Heard Island'
    WHEN 'MI' THEN 'Macquarie Island'
    WHEN 'AR' THEN 'Ashmore Reef and Cartier Islands'
    END as locality,
    'AU' as country_code,
    CASE WHEN r.name IS NOT NULL THEN
    CASE 
        WHEN replace(note.value, '.', '') LIKE '%?'||r.name||'%' THEN 'doubtful' 
        WHEN r.name='LHI' AND note.value LIKE '%?Lord Howe I.%' THEN 'doubtful'
        WHEN r.name='NI' AND note.value LIKE '%?Norfolk I.%' THEN 'doubtful'
        WHEN r.name='MI' AND note.value LIKE '%?Macquarie I.%' THEN 'doubtful'
        WHEN r.name='HI' AND note.value LIKE '%?Heard I.%' THEN 'doubtful'
        ELSE 'present' 
    END 
    ELSE null 
END as occurrence_status,
    CASE ds.name 
    WHEN 'native' THEN null 
    ELSE ds.name 
    END as establishment_means
FROM tnu_taxonomic_name_usages t
LEFT JOIN (
    SELECT n.instance_id, n.value
    FROM instance_note n
    JOIN instance_note_key nt ON n.instance_note_key_id=nt.id
    WHERE nt.name='distribution'
) as note ON t.id=note.instance_id
LEFT JOIN tree_element te ON t.id=te.instance_id
LEFT JOIN tree_element_distribution_entries tede ON te.id=tede.tree_element_id
LEFT JOIN dist_entry de ON tede.dist_entry_id=de.id
LEFT JOIN dist_region r ON de.region_id=r.id
LEFT JOIN dist_entry_dist_status deds ON de.id=deds.dist_entry_status_id
LEFT JOIN dist_status ds ON deds.dist_status_id=ds.id
WHERE r.name IS NOT NULL
UNION
SELECT te.id||'_99999999' as id, t.id as taxon_id, 'ISO:AQ' as locality_id, 'Antarctica' as locality, 'AQ' as country_code, 'present' as occurrence_status, null as establishment_means
FROM tnu_taxonomic_name_usages t
JOIN instance_note note ON t.id=note.instance_id
JOIN (
    SELECT te.id, te.instance_id
    FROM tree_element te
    JOIN tree_version_element tve ON te.id=tve.tree_element_id
    WHERE tve.tree_version_id = (
    SELECT tree_version.id
    FROM tree_version
    WHERE tree_version.published = true AND tree_version.published_at = ( 
        SELECT max(published_at) AS max
        FROM tree_version
        WHERE published = true
    )
    )
) as te ON t.id=te.instance_id
WHERE note.instance_note_key_id=10000003 AND note.value LIKE '%Ant%';
EOT;
        DB::statement($sql);
        $this->info('tnu_distribution view has been created.');
    }
}
