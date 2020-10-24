<?php

namespace App\Console\Commands;

use App\Tropicos\TropicosSearch as Search;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TropicosSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tropicos:search';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Searches for names in TROPICOS';

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
        $search = new Search();

        $names = DB::table('name')
                ->join('name_rank', 'name.name_rank_id', '=', 'name_rank.id')
                ->leftJoin('name as parent', 'name.parent_id', '=', 'parent.id')
                ->leftJoin('tropicos.ausmoss_names_tropicos_names as tn', 'name.id', '=', 'tn.ausmoss_name_id')
                ->whereNull('tn.id')
                ->select('name.id', 'name.simple_name', 'name.full_name', 
                        'parent.simple_name as parent_name', 
                        'name_rank.sort_order')
                ->orderBy('full_name')
                ->get();

        $bar = $this->output->createProgressBar($names->count());

        $bar->start();

        foreach ($names as $name) {
            $res = $search->search($name);
            $bar->advance();
        }

        $bar->finish();

        $this->info('Search completed');
    }
}
