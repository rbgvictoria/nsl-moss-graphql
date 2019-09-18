<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class CreateTaxonomicNamesCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tnu:create-csv:taxonomic-names';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create taxonomic names CSV file';

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
        $data = DB::table('tnu_taxonomic_names')->select(
            DB::raw("'https://id.biodiversity.org.au/name/ausmoss/' || id as id"),
            'taxonomic_name_string',
            'authorship',
            'taxonomic_name_string_with_authorship',
            DB::raw("'https://id.biodiversity.org.au/reference/ausmoss/' || name_published_in as name_published_in"),
            'micro_reference',
            'publication_year',
            'rank',
            'nomenclatural_status',
            DB::raw("'https://id.biodiversity.org.au/name/ausmoss/' || basionym as basionym"),
            DB::raw("'https://id.biodiversity.org.au/name/ausmoss/' || replaced_synonym as replaced_name")
        )->get();

        $csv = Writer::createFromString('');
        $header = [];
        foreach (array_keys((array) $data->first()) as $value) {
            $header[] = camel_case($value);
        }

        $csv->insertOne($header);

        foreach ($data as $row) {
            $csv->insertOne(array_values((array) $row));
        }

        Storage::put('tnu-datapackage/taxonomic_names.csv', $csv->getContent());
        $this->info('taxonomic_names_csv has been created.');
    }
}
