<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateTaxonomicNameUsagesViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tnu:create:taxonomic-name-usages-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create tnu_taxonomic_name_usages view.';

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
CREATE OR REPLACE VIEW tnu_taxonomic_name_usages AS
SELECT i.id, 
  n.id as taxonomic_name,
  i.reference_id as according_to,
  'accepted' as taxonomic_status, 
  null as accepted_name_usage,
  pi.id as parent_name_usage,  
  n.simple_name as taxonomic_name_string, 
  null as accepted_name_usage_string, 
  pn.simple_name as parent_name_usage_string,
  CASE te.excluded WHEN true THEN 'excluded' ELSE 'present' END as occurrence_status
  
FROM tree_version_element tve
JOIN tree_element te ON tve.tree_element_id=te.id
JOIN instance i ON te.instance_id=i.id
JOIN name n ON i.name_id=n.id
LEFT JOIN tree_version_element ptve ON tve.parent_id=ptve.element_link
LEFT JOIN tree_element pte ON ptve.tree_element_id=pte.id
LEFT JOIN instance pi ON pte.instance_id=pi.id
LEFT JOIN name pn ON pi.name_id=pn.id

WHERE tve.tree_version_id=(
  SELECT id
  FROM tree_version
  WHERE published=true
  AND published_at=(
    SELECT max(published_at)
    FROM tree_version
    WHERE published=true
  )
)

UNION

SELECT si.id, 
  si.name_id as taxonomic_name, 
  si.reference_id as according_to,
  'synonym' as taxonomic_status, 
  i.id as accepted_name_usage, 
  null as parent_name_usage,  
  sn.simple_name as  taxonomic_name_string,
  n.simple_name as accepted_name_usage_string,
  null as parent_name_usage_string,
  null as occurrence_status

FROM tree_version_element tve
JOIN tree_element te ON tve.tree_element_id=te.id
JOIN instance i ON te.instance_id=i.id
JOIN name n ON i.name_id=n.id

JOIN instance si ON i.id=si.cited_by_id
JOIN instance_type sit ON si.instance_type_id=sit.id
JOIN name sn ON si.name_id=sn.id
JOIN all_names an ON sn.id=an.id AND sn.id=an.protonym 

WHERE tve.tree_version_id=(
  SELECT id
  FROM tree_version
  WHERE published=true
  AND published_at=(
    SELECT max(published_at)
    FROM tree_version
    WHERE published=true
  )
)
AND sit.name='taxonomic synonym'

UNION

SELECT i.id, 
  i.name_id as taxonomic_name, 
  i.reference_id as according_to,
  CASE WHEN it.name='status uncertain' THEN 'uncertain' ELSE 'accepted' END as taxonomic_status, 
  null as accepted_name_usage, 
  null as parent_name_usage,  
  n.simple_name as  taxonomic_name_string,
  null as accepted_name_usage_string,
  null as parent_name_usage_string,
  CASE WHEN it.name='excluded name' THEN 'excluded' WHEN it.name='occurrence doubtful' THEN 'doubtful' ELSE null END as occurrence_status
FROM instance i
JOIN instance_type it ON i.instance_type_id=it.id
JOIN name n ON i.name_id=n.id
WHERE it.name IN ('status uncertain', 'excluded name', 'occurrence doubtful')

ORDER BY taxonomic_name_string
EOT;
        DB::statement($sql);
        $this->info('View public.tnu_taxonomic_name_usages view has been created.');
    }
}
