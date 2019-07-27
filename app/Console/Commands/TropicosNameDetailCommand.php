<?php

namespace App\Console\Commands;

use App\Tropicos\TropicosNameDetail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TropicosNameDetailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tropicos:get:name-detail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets name details through TROPICOS web service';

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
        $name = new TropicosNameDetail();

        $ids = DB::table('tropicos.tropicos_names')
                ->whereNull('genus')
                ->pluck('tropicos_name_id');

        $bar = $this->output->createProgressBar($ids->count());

        $bar->start();

        foreach ($ids as $id) {
            $name->getNameDetail($id);
            $bar->advance();
        }

        $bar->finish();

        $this->info('Harvest completed');
    }
}
