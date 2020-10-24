<?php

namespace App\Console\Commands;

use App\Tropicos\TropicosAcceptedNames;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TropicosAcceptedNamesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tropicos:get:accepted-names';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets accepted names through Tropicos web service';

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
        $name = new TropicosAcceptedNames;

        $ids = DB::table('tropicos.tropicos_names as n')
                ->leftJoin('tropicos.tropicos_accepted_names as an', 'n.id', '=', 'an.tropicos_name_id')
                ->where('n.accepted_name_count', '>', 0)
                ->whereNull('an.id')
                ->pluck('n.id');

        $bar = $this->output->createProgressBar($ids->count());

        $bar->start();

        foreach ($ids as $id) {
            $name->getAcceptedNames($id);
            $bar->advance();
        }

        $bar->finish();

        $this->info('Harvest completed');
    }
}
