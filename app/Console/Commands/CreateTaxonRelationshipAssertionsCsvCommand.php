<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class CreateTaxonRelationshipAssertionsCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tnu:create-csv:taxon-relationship-assertions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create taxon relationship assertions CSV file';

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
        $csv = Writer::createFromString('');

        $header = ['id', 
            'subjectTaxonomicNameUsage', 
            'relationshipType', 
            'objectTaxonomicNameUsage', 
            'accordingTo'];
        $csv->insertOne($header);

        $row = [
            'https://id.biodiversity.org.au/assertion/ausmoss/1',
            'https://id.biodiversity.org.au/instance/ausmoss/10144112',
            'isCongruentWith',
            'https://id.biodiversity.org.au/instance/ausmoss/10144105',
            'https://id.biodiversity.org.au/reference/ausmoss/10144104'
        ];
        $csv->insertOne($row);

        Storage::put('tnu-datapackage/data/taxon_relationship_assertions.csv', $csv->getContent());
        $this->info('taxon_relationship_assertions.csv has been created.');
    }
}
