<?php

namespace App\Console\Commands;

use App\Datapackage\TableSchema;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CreateDatapackageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tnu:create:datapackage {--data-set-name=datapackage} {--data-set-version=1.0}';

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
        $this->info('Write taxonomic_name_usages.csv');
        Artisan::call('tnu:create-csv:taxonomic-name-usages');

        $this->info('Write taxonomic_names.csv');
        Artisan::call('tnu:create-csv:taxonomic-names');
        
        $this->info('Write taxon_relationship_assertions.csv');
        Artisan::call('tnu:create-csv:taxon-relationship-assertions');
        
        $this->info('Write distribution.csv');
        Artisan::call('tnu:create-csv:distribution');
        
        $this->info('Write bibliographic_resources.csv');
        Artisan::call('tnu:create-csv:bibliographic-resources');
        
        $this->info('Write agents.csv');
        Artisan::call('tnu:create-csv:agents');
        
        $this->info('Copy datapackage.json');
        copy(resource_path() . '/datapackage/datapackage.json', storage_path('App') . '/tnu-datapackage/datapackage.json');

        $this->info('Create datapackage');
        TableSchema::zipIt(storage_path('App') . '/' .$this->option('data-set-name') . '-v' . $this->option('data-set-version') . '.zip');

        $this->info('Done');
    }
}
