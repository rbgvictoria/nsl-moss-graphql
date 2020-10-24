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
n.simple_name AS taxonomic_name_string,
    CASE
        WHEN n.author_id IS NOT NULL THEN "substring"(n.full_name::text, length(n.simple_name::text) + 2)
        ELSE NULL::text
    END AS authorship,
n.full_name AS taxonomic_name_string_with_authorship,
pub.primary_instance_id,
pub.reference_id AS name_published_in,
pub.page AS micro_reference,
pub.year AS publication_year,
    CASE nr.name
        WHEN 'Regnum'::text THEN 'kingdom'::text
        WHEN 'Division'::text THEN 'phylum'::text
        WHEN 'Classis'::text THEN 'class'::text
        WHEN 'Subclassis'::text THEN 'subclass'::text
        WHEN 'Superordo'::text THEN 'superorder'::text
        WHEN 'Ordo'::text THEN 'order'::text
        WHEN 'Subordo'::text THEN 'suborder'::text
        WHEN 'Familia'::text THEN 'family'::text
        WHEN 'Subfamilia'::text THEN 'subfamily'::text
        WHEN 'Genus'::text THEN 'genus'::text
        WHEN 'Subgenus'::text THEN 'subgenus'::text
        WHEN 'Sectio'::text THEN 'section'::text
        WHEN 'Species'::text THEN 'species'::text
        WHEN 'Subspecies'::text THEN 'subspecies'::text
        WHEN 'Varietas'::text THEN 'variety'::text
        WHEN 'Forma'::text THEN 'form'::text
        ELSE NULL::text
    END AS rank,
    CASE
        WHEN ns.nom_inval = false THEN 'ICN'::text
        ELSE NULL::text
    END AS nomenclatural_code,
    CASE ns.name
        WHEN 'nom. inval.'::text THEN 'invalid'::text
        WHEN 'isonym'::text THEN 'invalid'::text
        WHEN 'nom. superfl.'::text THEN 'superfluous'::text
        WHEN 'nom. inval., pro syn.'::text THEN 'invalid'::text
        WHEN 'nom. et orth. cons.'::text THEN 'conserved'::text
        WHEN 'nom. inval., nom. nud.'::text THEN 'invalid'::text
        WHEN 'nom. illeg.'::text THEN 'illegitimate'::text
        WHEN 'orth. cons.'::text THEN 'legitimate'::text
        WHEN 'legitimate'::text THEN 'legitimate'::text
        WHEN 'nom. rej.'::text THEN 'legitimate'::text
        WHEN 'nom. cons.'::text THEN 'conserved'::text
        WHEN 'orth. var.'::text THEN 'legitimate'::text
        WHEN 'nom. illeg., nom. rej.'::text THEN 'illegitimate'::text
        ELSE NULL::text
    END AS nomenclatural_status,
pr.basionym,
pr.replaced_synonym,
COALESCE(pr.basionym, pr.replaced_synonym, n.id) AS protonym,
pr.basionym_string,
pr.replaced_synonym_string,
COALESCE(pr.basionym_string, pr.replaced_synonym_string, n.full_name) AS protonym_string,
COALESCE(pr.basionym_instance_id, pr.replaced_synonym_instance_id, pub.primary_instance_id) AS protonym_instance_id
FROM name n
 LEFT JOIN ( SELECT n_1.id,
        n_1.full_name,
        bas.id AS basionym,
        repl.id AS replaced_synonym,
        bas.full_name AS basionym_string,
        repl.full_name AS replaced_synonym_string,
        bas.cited_by_id AS basionym_instance_id,
        repl.cited_by_id AS replaced_synonym_instance_id
       FROM name n_1
         JOIN instance i ON n_1.id = i.name_id
         LEFT JOIN ( SELECT i_1.cited_by_id,
                n_2.id,
                n_2.full_name
               FROM name n_2
                 JOIN instance i_1 ON n_2.id = i_1.name_id
                 JOIN instance_type it ON i_1.instance_type_id = it.id
              WHERE it.name::text = 'basionym'::text) bas ON i.id = bas.cited_by_id
         LEFT JOIN ( SELECT i_1.cited_by_id,
                n_2.id,
                n_2.full_name
               FROM name n_2
                 JOIN instance i_1 ON n_2.id = i_1.name_id
                 JOIN instance_type it ON i_1.instance_type_id = it.id
              WHERE it.name::text = 'replaced synonym'::text) repl ON i.id = repl.cited_by_id
      WHERE bas.id IS NOT NULL OR repl.id IS NOT NULL) pr ON n.id = pr.id
 LEFT JOIN ( SELECT n_1.id, i.id as primary_instance_id,
        i.reference_id,
        i.page,
        r.year
       FROM name n_1
         JOIN instance i ON n_1.id = i.name_id
         JOIN instance_type it ON i.instance_type_id = it.id
         JOIN reference r ON i.reference_id = r.id
      WHERE it.primary_instance = true) pub ON n.id = pub.id
 LEFT JOIN name_rank nr ON n.name_rank_id = nr.id AND nr.name::text <> '[n/a]'::text
 LEFT JOIN name_status ns ON n.name_status_id = ns.id AND ns.name::text <> '[n/a]'::text
EOT;
        DB::statement($sql);
        $this->info('View public.all_names has been created.');
    }
}
