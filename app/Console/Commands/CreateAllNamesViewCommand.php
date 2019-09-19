<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateAllNamesViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tnu:create:all-names-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates tnu_all_names view';

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
CREATE OR REPLACE VIEW all_names AS
SELECT n.id,
    n.simple_name as taxonomic_name_string,
    CASE WHEN n.author_id IS NOT NULL THEN substring(n.full_name from length(n.simple_name)+2) ELSE null END as authorship, 
    n.full_name as taxonomic_name_string_with_authorship,
    pub.reference_id as name_published_in,
    pub.page as micro_reference,
    pub.year as publication_year,
    CASE nr.name 
    WHEN 'Regnum' THEN 'kingdom'
    WHEN 'Division' THEN 'phylum'
    WHEN 'Classis' THEN 'class'
    WHEN 'Subclassis' THEN 'subclass'
    WHEN 'Superordo' THEN 'superorder'
    WHEN 'Ordo' THEN 'order'
    WHEN 'Subordo' THEN 'suborder'
    WHEN 'Familia' THEN 'family'
    WHEN 'Subfamilia' THEN 'subfamily'
    WHEN 'Genus' THEN 'genus'
    WHEN 'Subgenus' THEN 'subgenus'
    WHEN 'Sectio' THEN 'section'
    WHEN 'Species' THEN 'species'
    WHEN 'Subspecies' THEN 'subspecies'
    WHEN 'Varietas' THEN 'variety'
    WHEN 'Forma' THEN 'form'
    END as rank,
    CASE WHEN ns.nom_inval=false THEN 'ICN' ELSE null END as nomenclatural_code,
    CASE ns.name
    WHEN 'nom. inval.' THEN 'invalid'
    WHEN 'isonym' THEN 'invalid'
    WHEN 'nom. superfl.' THEN 'superfluous'
    WHEN 'nom. inval., pro syn.' THEN 'invalid'
    WHEN 'nom. et orth. cons.' THEN 'conserved'
    WHEN 'nom. inval., nom. nud.' THEN 'invalid'
    WHEN 'nom. illeg.' THEN 'illegitimate'
    WHEN 'orth. cons.' THEN 'legitimate'
    WHEN 'legitimate' THEN 'legitimate'
    WHEN 'nom. rej.' THEN 'legitimate'
    WHEN 'nom. cons.' THEN 'conserved'
    WHEN 'orth. var.' THEN 'legitimate'
    WHEN 'nom. illeg., nom. rej.' THEN 'illegitimate'
    END as nomenclatural_status,
    pr.basionym, 
    pr.replaced_synonym, 
    coalesce(pr.basionym, pr.replaced_synonym, n.id) as protonym,
    pr.basionym_string, 
    pr.replaced_synonym_string, 
    coalesce(pr.basionym_string, pr.replaced_synonym_string, n.full_name) as protonym_string
FROM name n
LEFT JOIN (
    SELECT n.id, n.full_name, bas.id as basionym, repl.id as replaced_synonym, bas.full_name as basionym_string, repl.full_name as replaced_synonym_string
    FROM name n
    JOIN instance i ON n.id=i.name_id
    LEFT JOIN (
    SELECT i.cited_by_id, n.id, n.full_name
    FROM name n
    JOIN instance i ON n.id=i.name_id
    JOIN instance_type it ON i.instance_type_id=it.id
    WHERE it.name='basionym'
    ) bas ON i.id=bas.cited_by_id
    LEFT JOIN (
    SELECT i.cited_by_id, n.id, n.full_name
    FROM name n
    JOIN instance i ON n.id=i.name_id
    JOIN instance_type it ON i.instance_type_id=it.id
    WHERE it.name='replaced synonym'
    ) repl ON i.id=repl.cited_by_id
    WHERE (bas.id IS NOT NULL OR repl.id IS NOT NULL)
) as pr ON N.id=pr.id
LEFT JOIN (
    SELECT n.id, i.reference_id, i.page, r.year
    FROM name n
    JOIN instance i ON n.id=i.name_id
    JOIN instance_type it ON i.instance_type_id=it.id
    JOIN reference r ON i.reference_id=r.id
    WHERE it.primary_instance=true
) as pub ON n.id=pub.id
LEFT JOIN name_rank nr ON n.name_rank_id=nr.id AND nr.name!='[n/a]'
LEFT JOIN name_status ns ON n.name_status_id=ns.id AND ns.name!='[n/a]'
EOT;
        DB::statement($sql);
        $this->info('View public.all_names has been created.');
    }
}
