<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TropicosNameReferenceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tropicos:get:name-references';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets name references from Tropicos web service';

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
        $nameReference = new \App\Tropicos\TropicosNameReferences;
        $ids = DB::table('tropicos.tropicos_names as nam')
                ->leftJoin('tropicos.tropicos_names_references as namref', 'nam.id', '=', 'namref.tropicos_name_id')
                ->whereNull('namref.id')
                ->pluck('nam.id');
        $bar = $this->output->createProgressBar($ids->count());
        $bar->start();
        foreach ($ids as $id) {
            $nameReference->getNameReferences($id);
            $bar->advance();
        }
        $bar->finish();
        $this->info('Command completed');
    }
}
