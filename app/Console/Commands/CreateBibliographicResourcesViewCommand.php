<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateBibliographicResourcesViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tnu:create:bibliographic-resources-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create tnu_bibliographic_resources_view.';

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
CREATE OR REPLACE VIEW tnu_bibliographic_resources AS
SELECT 
    r.id,
    rt.name as reference_type,
    a.name as creator, 
    coalesce(r.year, substring(r.iso_publication_date from 1 for 4)::integer) as publication_year, 
    r.title, 
    r.parent_id as parent,
    r.volume,
    r.pages,
    r.publisher,
    r.published_location
FROM reference r
LEFT JOIN author a ON r.author_id=a.id AND a.name!='-'
JOIN (
    SELECT r.id
    FROM reference r
    LEFT JOIN author a ON r.author_id=a.id AND a.name!='-'
    JOIN (
    SELECT according_to as id
    FROM tnu_taxonomic_name_usages
    UNION
    SELECT name_published_in
    FROM tnu_taxonomic_names
    ) tctn ON r.id=tctn.id
    UNION
    SELECT pr.id
    FROM reference r
    LEFT JOIN author a ON r.author_id=a.id AND a.name!='-'
    JOIN (
    SELECT according_to as id
    FROM tnu_taxonomic_name_usages
    UNION
    SELECT name_published_in
    FROM tnu_taxonomic_names
    ) tctn ON r.id=tctn.id
    JOIN reference pr ON r.parent_id=pr.id
) incl ON r.id=incl.id
JOIN ref_type rt ON r.ref_type_id=rt.id
EOT;
        DB::statement($sql);
        $this->info('View public.tnu_bibliographic_resources has been created.');
    }
}
