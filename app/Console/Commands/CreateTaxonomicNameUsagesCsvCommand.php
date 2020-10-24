<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class CreateTaxonomicNameUsagesCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tnu:create-csv:taxonomic-name-usages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create taxonomic name usages CSV file.';

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
        $data = DB::table('tnu_taxonomic_name_usages')->select(
                    DB::raw("'https://id.biodiversity.org.au/instance/ausmoss/' || id as id"),
                    DB::raw("'https://id.biodiversity.org.au/name/ausmoss/' || taxonomic_name as taxonomic_name"),
                    DB::raw("'https://id.biodiversity.org.au/reference/ausmoss/' || according_to as according_to"),
                    'taxonomic_status',
                    DB::raw("'https://id.biodiversity.org.au/instance/ausmoss/' || accepted_name_usage as accepted_name_usage"),
                    DB::raw("'https://id.biodiversity.org.au/instance/ausmoss/' || parent_name_usage as parent_name_usage")
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

        Storage::put('tnu-datapackage/data/taxonomic_name_usages.csv', $csv->getContent(), 'public');
    }
}
