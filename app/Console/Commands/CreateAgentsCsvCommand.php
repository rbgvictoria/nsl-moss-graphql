<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class CreateAgentsCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tnu:create-csv:agents';

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
        $data = DB::table('tnu_bibliographic_resources')
                ->whereNotNull('agent_id')
                ->select(
                    DB::raw("'https://id.biodiversity.org.au/author/ausmoss/'||agent_id as id"),
                    'creator as name'
                )->distinct()->get();
        $csv = Writer::createFromString('');
        $header = [];
        foreach (array_keys((array) $data->first()) as $value) {
            $header[] = camel_case($value);
        }

        $csv->insertOne($header);

        foreach ($data as $row) {
            $csv->insertOne(array_values((array) $row));
        }

        Storage::put('tnu-datapackage/data/agents.csv', $csv->getContent(), 'public');
        $this->info('agents.csv has been created');
    }
}
