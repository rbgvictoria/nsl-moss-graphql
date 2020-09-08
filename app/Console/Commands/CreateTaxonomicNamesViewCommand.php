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
SELECT id,
  taxonomic_name_string, 
  authorship, 
  taxonomic_name_string_with_authorship, 
  name_published_in,
  micro_reference,
  publication_year,
  rank,
  nomenclatural_code,
  nomenclatural_status,
  basionym,
  replaced_synonym
FROM all_names
EOT;
        DB::statement($sql);
        $this->info('View public.tnu_taxonomic_names has been created.');
    }
}
