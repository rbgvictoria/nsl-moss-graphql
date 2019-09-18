<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class CreateDistributionCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tnu:create-csv:distribution';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $data = DB::table('tnu_distribution')->select(
            DB::raw("'https://id.biodiversity.org.au/dist/ausmoss/' || id as id"),
            DB::raw("'https://id.biodiversity.org.au/instance/ausmoss/' || taxon_id as taxon_id"),
            'locality_id as location_id',
            'locality',
            'country_code',
            'occurrence_status',
            'establishment_means'
        )->get();
        $csv = Writer::createFromString('');
        $header = [];
        foreach (array_keys((array) $data->first()) as $key) {
            $header[] = camel_case($key);
        }
        $csv->insertOne($header);
        foreach ($data as $row) {
            $csv->insertOne((array_values((array) $row)));
        }
        Storage::put('tnu-datapackage/data/distribution.csv', $csv->getContent());
    }
}
