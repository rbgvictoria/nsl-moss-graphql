<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class CreateBibliographicResourcesCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tnu:create-csv:bibliographic-resources';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create bibliographic resources CSV file.';

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
        $data = DB::table('tnu_bibliographic_resources')->select(
            DB::raw("'https://id.biodiversity.org.au/reference/ausmoss/' || id as id"),
            'type',
            DB::raw("'https://id.biodiversity.org.au/author/ausmoss/' || agent_id as creator"),
            'publication_year as created',
            'title',
            DB::raw("'https://id.biodiversity.org.au/reference/ausmoss/' || parent as is_part_of"),
            'volume',
            'pages',
            'publisher'
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

        Storage::put('tnu-datapackage/data/bibliographic_resources.csv', $csv->getContent(), 'public');
        $this->info('bibliographic_resources.csv has been created');
    }
}
