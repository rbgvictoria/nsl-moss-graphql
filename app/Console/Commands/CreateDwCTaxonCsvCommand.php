<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class CreateDwCTaxonCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dwc:create-csv:dwc-taxon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CSV from dwc_taxon view';

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
        $data = DB::table('dwc_taxon')->select(
            'taxon_id', 'scientific_name_id', 'scientific_name', 'scientific_name_authorship',
            'name_published_in_id', 'name_published_in', 'name_published_in_year',
            'name_according_to_id', 'name_according_to', 'original_name_usage_id',
            'original_name_usage', 'accepted_name_usage_id', 'accepted_name_usage',
            'parent_name_usage_id', 'parent_name_usage', 'taxon_rank', 
            'taxonomic_status', 'occurrence_status', 'higher_classification' 
        )->get();

    $csv = Writer::createFromString('');
    $header = [];
    foreach (array_keys((array) $data->first()) as $value) {
        $header[] = $value;
    }

    $csv->insertOne($header);

    foreach ($data as $row) {
        $csv->insertOne(array_values((array) $row));
    }

    Storage::put('dwc/dwc_taxon.csv', $csv->getContent(), 'public');
    }
}
