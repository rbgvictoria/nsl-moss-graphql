<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateTaxonomicNamesViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tnu:create:taxonomic-names-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create tnu_taxonomic_names view.';

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
CREATE OR REPLACE VIEW tnu_taxonomic_names AS
SELECT tn.id,
  tn.taxonomic_name_string, 
  tn.authorship, 
  tn.taxonomic_name_string_with_authorship, 
  tn.name_published_in,
  tn.micro_reference,
  tn.publication_year,
  tn.rank,
  tn.nomenclatural_code,
  tn.nomenclatural_status,
  tn.basionym,
  tn.replaced_synonym
FROM tnu_taxonomic_name_usages tc
JOIN all_names n ON tc.taxonomic_name=n.id
JOIN all_names tn ON n.protonym=tn.protonym
GROUP BY tn.id,
  tn.taxonomic_name_string, 
  tn.authorship, 
  tn.taxonomic_name_string_with_authorship, 
  tn.name_published_in,
  tn.micro_reference,
  tn.publication_year,
  tn.rank,
  tn.nomenclatural_code,
  tn.nomenclatural_status,
  tn.basionym,
  tn.replaced_synonym
EOT;
        DB::statement($sql);
        $this->info('View public.tnu_taxonomic_names has been created.');
    }
}
