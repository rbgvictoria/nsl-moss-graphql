<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class RecreateViewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tnu:recreate-views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop and re-create all views';

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
        $this->info('Drop views...');
        DB::statement('DROP VIEW IF EXISTS all_names CASCADE');

        $this->info('Create all_names view...');
        Artisan::call('tnu:create:all-names-view');
        
        $this->info('Create tnu_taxonomic_name_usages view...');
        Artisan::call('tnu:create:taxonomic-name-usages-view');
        
        $this->info('Create tnu_taxonomic_names view...');
        Artisan::call('tnu:create:taxonomic-names-view');
        
        $this->info('Create tnu_bibliographic_resources view...');
        Artisan::call('tnu:create:bibliographic-resources-view');

        $this->info('Done.');
    }
}
